import re
import sys
p = re.compile(r"@if\b|@elseif\b|@else\b|@endif\b|@foreach\b|@endforeach\b")
stack = []
orphans = []
lines = open('web/resources/views/admin.blade.php',encoding='utf-8').read().splitlines()
for i,ln in enumerate(lines, start=1):
    for m in p.finditer(ln):
        tok = m.group(0)
        if tok == '@if':
            stack.append((tok,i,ln.strip()))
        elif tok == '@foreach':
            stack.append((tok,i,ln.strip()))
        elif tok == '@elseif' or tok == '@else':
            if not stack:
                orphans.append((tok,i,ln.strip()))
            else:
                # ok, else/elseif belong to last if
                pass
        elif tok == '@endif' or tok == '@endforeach':
            # pop matching opening
            if not stack:
                orphans.append((tok,i,ln.strip()))
            else:
                # pop until matching type
                last = stack.pop()
                # if mismatch foreach/endforeach or if/endif, ok; else continue
                # no extra checks here
                pass

print('Totals: if/foreach stack remaining:', len(stack))
if stack:
    print('Unclosed openings:')
    for tok,line,txt in stack:
        print(f"{tok} at line {line}: {txt}")
if orphans:
    print('\nOrphan tokens (no matching open):')
    for tok,line,txt in orphans:
        print(f"{tok} at line {line}: {txt}")

# Also print the last 100 lines for quick context
print('\n--- tail (last 100 lines) ---')
for ln in lines[-100:]:
    print(ln)
