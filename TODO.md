# TODO - Remove tahun field from DocumentResource

## Steps:
- [x] 1. Remove `tahun` field from DocumentResource form
- [x] 2. Remove `tahun` from `$fillable` in Document model
- [x] 3. Clear config cache

## Notes:
- The `tahun` field should not be stored directly in `documents` table
- `TahunAjaran` is a separate table that can be used for reference if needed

