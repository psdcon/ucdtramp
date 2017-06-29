import sys


filename = sys.argv[1]

print("Opening file:", filename)

with open(filename, 'r') as f:
    lines = f.readlines()

new_lines = lines[::2]
print new_lines

# print("Would you like to write the file")

with open(filename, "w") as f:
    f.writelines(new_lines)
