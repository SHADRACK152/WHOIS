$targetPath = "c:\Users\Trova\Downloads\WHOISS\api"
$files = Get-ChildItem -Path $targetPath -Filter "*.php"

foreach ($file in $files) {
    if ($file.Name -like "_*") { continue }
    $content = Get-Content $file.FullName -Raw
    
    # Correct paths: ../../app/ to ../app/
    # This matches the move from public/api/ (depth 2) to api/ (depth 1)
    $newContent = $content -replace "\.\./\.\./app/", "../app/"
    
    if ($content -ne $newContent) {
        $newContent | Set-Content $file.FullName
        Write-Output "Corrected paths in API: $($file.Name)"
    }
}
