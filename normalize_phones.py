import csv, re, sys

input_path = r"C:\Users\vinay\OneDrive\Desktop\SD\astrochitra_slots\phones.csv"
output_path = "phones_cleaned.csv"

def normalize(phone):
    p = phone.strip()
    # remove spaces, dashes
    p_clean = re.sub(r'[\s\-]', '', p)
    if p_clean.startswith('+'):
        return p_clean
    # if length >10 treat as includes country code
    if len(p_clean) > 10:
        return '+' + p_clean
    # length ==10 => Indian
    if len(p_clean) == 10:
        return '+91' + p_clean
    # fallback
    return '+' + p_clean

with open(input_path, newline='', encoding='utf-8') as infile, open(output_path, 'w', newline='', encoding='utf-8') as outfile:
    reader = csv.DictReader(infile)
    fieldnames = ['name','phone_original','phone_corrected']
    writer = csv.DictWriter(outfile, fieldnames=fieldnames)
    writer.writeheader()
    for row in reader:
        orig = row['phone']
        corr = normalize(orig)
        writer.writerow({'name': row['name'], 'phone_original': orig, 'phone_corrected': corr})
print("Done")