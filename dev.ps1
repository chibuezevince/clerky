$projectDir = "C:\Users\micha\Documents\Projects\clerky"
$php = "C:\Users\micha\.config\herd\bin\php84\php.exe"

Write-Host "Starting all services..."

Start-Process -NoNewWindow -FilePath "cmd.exe" -ArgumentList "/c","cd /d `"$projectDir`" && npm run dev"
Start-Process -NoNewWindow -FilePath "cmd.exe" -ArgumentList "/c","cd /d `"$projectDir`" && `"$php`" artisan queue:work --queue=jobs,kyc-verification,emails,listeners --tries=3"
Start-Process -NoNewWindow -FilePath "cmd.exe" -ArgumentList "/c","mailpit"

Write-Host "All services launched. Press Ctrl+C to stop."