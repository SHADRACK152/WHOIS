$targetPath = "c:\Users\Trova\Downloads\WHOISS\backend\public\pages"
$files = Get-ChildItem -Path $targetPath -Filter "*.php"

foreach ($file in $files) {
    if ($file.Name -like "_*") { continue }
    $content = Get-Content $file.FullName -Raw
    $newContent = $content
    
    # 1. Standardize Asset paths (images, fonts, css, js)
    # Replaces "/assets/..." with "<?=$assetBase?>/assets/..."
    $newContent = $newContent -replace '"/assets/', '"<?=$assetBase?>/assets/'
    $newContent = $newContent -replace "'/assets/", "'<?=$assetBase?>/assets/"
    
    # 2. Standardize API paths (fetch calls, etc.)
    # Replaces "/api/..." with "<?=$assetBase?>/api/..."
    $newContent = $newContent -replace '"/api/', '"<?=$assetBase?>/api/'
    $newContent = $newContent -replace "'/api/", "'<?=$assetBase?>/api/"
    
    # 3. Standardize Page links (sometimes they have /pages/)
    # We want these to be relative too so they work everywhere.
    # Replaces "/pages/..." with "<?=$assetBase?>/pages/..."
    $newContent = $newContent -replace '"/pages/', '"<?=$assetBase?>/pages/'
    $newContent = $newContent -replace "'/pages/", "'<?=$assetBase?>/pages/"

    if ($content -ne $newContent) {
        $newContent | Set-Content $file.FullName
        Write-Output "Standardized paths in: $($file.Name)"
    }
}
