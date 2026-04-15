$ErrorActionPreference = 'Stop'

function Escape-PhpString([string]$s) {
  if ($null -eq $s) { return '' }
  return ($s -replace "'", "\'")
}

function Get-ContinentCode([string]$region, [string]$subregion) {
  $regionValue = ''
  $subregionValue = ''

  if ($null -ne $region) { $regionValue = [string]$region }
  if ($null -ne $subregion) { $subregionValue = [string]$subregion }

  $r = $regionValue.ToLowerInvariant()
  $s = $subregionValue.ToLowerInvariant()

  switch ($r) {
    'africa' { return 'africa' }
    'asia' { return 'asia' }
    'europe' { return 'europe' }
    'oceania' { return 'australia' }
    'americas' {
      if ($s -match 'south') { return 'south-america' }
      return 'north-america'
    }
    default { return 'north-america' }
  }
}

$repoRoot = Split-Path -Parent $PSScriptRoot
$targetFile = Join-Path $repoRoot 'backend/app/dns-checker-nodes.php'

$dnsUrl = 'https://public-dns.info/nameservers-all.json'
$countriesUrl = 'https://restcountries.com/v3.1/all?fields=cca2,name,latlng,region,subregion'

Write-Output 'Downloading resolver dataset...'
$dnsRows = (Invoke-WebRequest -Uri $dnsUrl -UseBasicParsing -TimeoutSec 90).Content | ConvertFrom-Json

Write-Output 'Downloading country metadata...'
$countryRows = (Invoke-WebRequest -Uri $countriesUrl -UseBasicParsing -TimeoutSec 90).Content | ConvertFrom-Json

$countryMeta = @{}
foreach ($c in $countryRows) {
  $code = [string]$c.cca2
  if ([string]::IsNullOrWhiteSpace($code)) { continue }

  $name = ''
  if ($c.name -and $c.name.common) { $name = [string]$c.name.common }
  if ([string]::IsNullOrWhiteSpace($name)) { $name = $code.ToUpperInvariant() }

  $lat = 0.0
  $lon = 0.0
  if ($c.latlng -and $c.latlng.Count -ge 2) {
    $lat = [double]$c.latlng[0]
    $lon = [double]$c.latlng[1]
  }

  $countryMeta[$code.ToUpperInvariant()] = [pscustomobject]@{
    name = $name
    lat = $lat
    lon = $lon
    region = [string]$c.region
    subregion = [string]$c.subregion
  }
}

$ipv4Pattern = '^(?:\d{1,3}\.){3}\d{1,3}$'
$filtered = @()
foreach ($row in $dnsRows) {
  $ip = [string]$row.ip
  $country = ([string]$row.country_id).ToUpperInvariant()
  $city = [string]$row.city
  $asOrg = [string]$row.as_org
  $name = [string]$row.name
  $error = [string]$row.error

  if ($ip -notmatch $ipv4Pattern) { continue }
  if ($country.Length -ne 2) { continue }
  if (-not $countryMeta.ContainsKey($country)) { continue }
  if (-not [string]::IsNullOrWhiteSpace($error)) { continue }

  $rel = 0.0
  if ($null -ne $row.reliability) {
    try { $rel = [double]$row.reliability } catch { $rel = 0.0 }
  }

  if ($rel -lt 0.92) { continue }

  $provider = if (-not [string]::IsNullOrWhiteSpace($asOrg)) { $asOrg } elseif (-not [string]::IsNullOrWhiteSpace($name)) { $name } else { 'Public Resolver' }
  $meta = $countryMeta[$country]
  $location = if (-not [string]::IsNullOrWhiteSpace($city)) { "$city, $($meta.name)" } else { [string]$meta.name }

  $filtered += [pscustomobject]@{
    ip = $ip
    country = $country.ToLowerInvariant()
    countryName = [string]$meta.name
    continent = Get-ContinentCode -region ([string]$meta.region) -subregion ([string]$meta.subregion)
    location = $location
    provider = $provider
    lat = [double]$meta.lat
    lon = [double]$meta.lon
    reliability = $rel
  }
}

$byCountry = $filtered | Group-Object country
$selected = @()
foreach ($g in $byCountry) {
  $top = $g.Group | Sort-Object -Property @{Expression='reliability';Descending=$true} | Select-Object -First 1
  if ($null -ne $top) { $selected += $top }
}

$maxNodes = 160
if ($selected.Count -lt $maxNodes) {
  $selectedIps = @{}
  foreach ($s in $selected) { $selectedIps[$s.ip] = $true }

  $extras = $filtered |
    Where-Object { -not $selectedIps.ContainsKey($_.ip) } |
    Sort-Object -Property @{Expression='reliability';Descending=$true} |
    Select-Object -First ($maxNodes - $selected.Count)

  $selected += $extras
}

$selected = $selected | Sort-Object -Property continent, country, location

$lines = @()
$lines += '<?php'
$lines += ''
$lines += 'declare(strict_types=1);'
$lines += ''
$lines += 'function whois_dns_checker_nodes(): array'
$lines += '{'
$lines += '    return ['

$idx = 1
foreach ($n in $selected) {
  $markerId = "marker_$idx"
  $continent = Escape-PhpString([string]$n.continent)
  $country = Escape-PhpString([string]$n.country)
  $countryName = Escape-PhpString([string]$n.countryName)
  $location = Escape-PhpString([string]$n.location)
  $provider = Escape-PhpString([string]$n.provider)
  $resolver = Escape-PhpString([string]$n.ip)
  $lat = ([double]$n.lat).ToString('0.0000', [System.Globalization.CultureInfo]::InvariantCulture)
  $lon = ([double]$n.lon).ToString('0.0000', [System.Globalization.CultureInfo]::InvariantCulture)

  $lines += "        ['markerId' => '$markerId', 'continent' => '$continent', 'country' => '$country', 'countryName' => '$countryName', 'location' => '$location', 'provider' => '$provider', 'resolver' => '$resolver', 'lat' => $lat, 'lon' => $lon],"
  $idx++
}

$lines += '    ];'
$lines += '}'
$lines += ''

$utf8NoBom = New-Object System.Text.UTF8Encoding($false)
[System.IO.File]::WriteAllLines($targetFile, $lines, $utf8NoBom)

Write-Output ("Generated nodes: " + $selected.Count)
Write-Output ("Countries covered: " + (($selected | Group-Object country).Count))
Write-Output ("Updated file: " + $targetFile)
