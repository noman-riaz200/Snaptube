# TODO: Add APK Upload Option to Admin Dashboard

## Plan Overview
Add a second option to 'Upload an APK File' next to the 'Download URL' input in the Core PHP admin dashboard. The form must allow the admin to either enter a URL OR upload a file, but NOT both. Add PHP validation on form submission: if both a URL is entered AND a file is uploaded, display an error message and prevent saving. The system should only save the context if exactly one option is chosen. Update the frontend 'Download Snaptube' button to dynamically link to either the saved URL or the uploaded file path automatically.

## Steps

### Step 1: Update database schema ✅
- File: `database.sql`
- Add `apk_file_path VARCHAR(255) DEFAULT ''` column to `site_settings`
- Update default INSERT row

### Step 2: Update config functions ✅
- File: `config.php`
- Add `'apk_file_path'` to `$allowed` arrays in `getSetting()` and `updateSettings()`

### Step 3: Create uploads directory with security ✅
- New directory: `uploads/`
- New file: `uploads/.htaccess` (deny PHP execution)

### Step 4: Update admin dashboard form & validation ✅
- File: `admin/index.php`
- Add `enctype="multipart/form-data"` to form
- Add APK file upload field with `accept=".apk"`
- Remove `required` from download_url
- Add PHP validation: error if both/neither provided
- Handle file upload: validate .apk extension, move to uploads/
- Clear the other field when one is used
- Show current uploaded file info if exists
- Add inline JS for UX mutual exclusivity

### Step 5: Update frontend download link ✅
- File: `header.php`
- Fetch `apk_file_path`
- Set `$downloadUrl` to prefer file path over URL

### Step 6: Run database migration & test scenarios ✅
- Run ALTER TABLE or recreate DB to add `apk_file_path` column
- Test URL only → success
- Test File only → success
- Test Both → error
- Test Neither → error
- Frontend button links correctly

