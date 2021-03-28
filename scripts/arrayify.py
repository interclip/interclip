sanitizedLines = []

with open('diff.txt') as f:
    for line in f:
        sanitizedLines.append("https://interclip.app/"+line.strip())

print(str(sanitizedLines))