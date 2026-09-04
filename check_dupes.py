import csv, collections

path = r"C:\Users\vinay\OneDrive\Desktop\SD\astrochitra-newsletter-demo\phones_cleaned.csv"
with open(path, newline='', encoding='utf-8') as f:
    reader = csv.DictReader(f)
    counts = collections.Counter(row['phone_corrected'] for row in reader)

dupes = {k:v for k,v in counts.items() if v>1}
print("duplicate numbers:", len(dupes))
for k,v in list(dupes.items())[:20]:
    print(k, v)