$targetPath = "c:\Users\Trova\Downloads\WHOISS\backend\public\pages"
$files = Get-ChildItem -Path $targetPath -Filter "*.php"

foreach ($file in $files) {
    if ($file.Name -like "_*") { continue }
    $content = Get-Content $file.FullName -Raw
    
    # Fix the corrupted tag
    $newContent = $content -replace '<\?=>', '<?=$assetBase?>'
    
    if ($content -ne $newContent) {
        $newContent | Set-Content $file.FullName
        Write-Output "Fixed tag corruption in: $($file.Name)"
    }
}
