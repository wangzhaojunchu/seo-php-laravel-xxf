$path = 'web/resources/views/admin.blade.php'
$lines = Get-Content $path -Encoding UTF8
$pattern = '@if\b|@elseif\b|@else\b|@endif\b|@foreach\b|@endforeach\b'
$stack = @()
$orphans = @()
for($i=0;$i -lt $lines.Count; $i++){
    $ln = $lines[$i]
    $matches = [regex]::Matches($ln, $pattern)
    foreach($m in $matches){
        $tok = $m.Value
        switch ($tok) {
            '@if' { $stack += @{tok=$tok; line=$i+1; text=$ln.Trim()} }
            '@foreach' { $stack += @{tok=$tok; line=$i+1; text=$ln.Trim()} }
            '@elseif' { if($stack.Count -eq 0){ $orphans += @{tok=$tok; line=$i+1; text=$ln.Trim()} } }
            '@else' { if($stack.Count -eq 0){ $orphans += @{tok=$tok; line=$i+1; text=$ln.Trim()} } }
            '@endif' { if($stack.Count -gt 0){ $stack = $stack[0..($stack.Count-2)] } else { $orphans += @{tok=$tok; line=$i+1; text=$ln.Trim()} } }
            '@endforeach' { if($stack.Count -gt 0){ $stack = $stack[0..($stack.Count-2)] } else { $orphans += @{tok=$tok; line=$i+1; text=$ln.Trim()} } }
        }
    }
}
"Stack remaining count: $($stack.Count)" | Out-File blade_scan_result.txt -Encoding UTF8
if($stack.Count -gt 0){ "Unclosed openings:" | Out-File blade_scan_result.txt -Encoding UTF8 -Append; foreach($s in $stack){ "${($s.tok)} at line ${($s.line)}: ${($s.text)}" | Out-File blade_scan_result.txt -Encoding UTF8 -Append } }
if($orphans.Count -gt 0){ "Orphan tokens:" | Out-File blade_scan_result.txt -Encoding UTF8 -Append; foreach($o in $orphans){ "${($o.tok)} at line ${($o.line)}: ${($o.text)}" | Out-File blade_scan_result.txt -Encoding UTF8 -Append } }
"--- tail 80 lines ---" | Out-File blade_scan_result.txt -Encoding UTF8 -Append
Get-Content $path -Tail 80 | Out-File blade_scan_result.txt -Encoding UTF8 -Append
Get-Content blade_scan_result.txt -Encoding UTF8
