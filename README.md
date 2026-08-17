# Homepage A/B Test

A self-contained folder you can upload to any standard PHP web host to run
an A/B test between two homepage designs.

## What's inside

- `index.php` — visitors see both homepage images and pick one
- `respond.php` — the follow-up form (email, follow-up permission, reason)
- `submit.php` — saves the response and shows the thank-you message
- `results.php` — password-protected dashboard for designers (counts + recent responses)
- `export.php` — password-protected CSV download of all responses
- `login.php` / `logout.php` — simple password gate for the dashboard
- `images/option-a.png`, `images/option-b.png` — **placeholder images, replace these**
- `data/responses.sqlite` — the response database (created automatically on first submission)

No MySQL setup needed — responses are stored in a small SQLite file inside `data/`,
which is blocked from direct web access via `data/.htaccess`.

## Setup

1. **Replace the placeholder images.** Swap `images/option-a.png` and
   `images/option-b.png` with your real homepage screenshots (keep the same
   filenames, or update the filenames in `config.php`).

2. **Set a real password.** Open `config.php` and change:
   ```php
   define('RESULTS_PASSWORD', 'changeme123');
   ```
   to something only you and your team know.

3. **(Optional) Update the labels.** Also in `config.php`, `OPTION_A_LABEL`
   and `OPTION_B_LABEL` are shown to visitors and on the results page.

4. **Upload the whole `ab-test` folder** to your web host (e.g. via FTP/SFTP
   or your host's file manager) into wherever you want it to live, for
   example `yoursite.com/ab-test/`.

5. **Check requirements.** Your host needs PHP with the `pdo_sqlite`
   extension enabled — this is on by default on almost all standard PHP
   hosting (cPanel, etc). If `index.php` errors out, ask your host to confirm
   `pdo_sqlite` is enabled.

6. **Make sure `data/` is writable.** Most hosts allow this by default. If
   you get a database error, set the `data` folder's permissions to `755`
   (or `775` if needed) via your host's file manager.

7. Visit `yoursite.com/ab-test/` to test the visitor flow, and
   `yoursite.com/ab-test/results.php` to log in and see results.

## How it works

- A visitor picks Option A or B on the homepage.
- They're taken to a short form: optional email, a yes/no on whether it's
  okay to follow up, and a text box for why they chose that option.
- On submit, the response is saved and they see a thank-you message
  (plus "We will be in touch" if they said yes to follow-up).
- You (the designer) log in at `results.php` with the password from
  `config.php` to see the vote breakdown and the most recent 100 written-in
  reasons.
- `export.php` streams a full CSV of every response (all columns, not just
  the recent 100) so you can open it in Excel/Google Sheets.

## Duplicate-vote prevention

After someone submits a response, they get a long-lived cookie
(`ab_test_voted`) and their IP address is recorded. If they try to vote
again — cookie still present, or same IP already has a response — they see
a friendly "you've already responded" message instead of the form. This is
checked both before the form is shown and again on submit, so it can't be
bypassed by linking straight to `submit.php`.

This isn't foolproof (clearing cookies or a shared office/home IP can get
around it), but it stops casual repeat voting. IP addresses are stored per
response and included in the CSV export, but not shown on the results page.

## Notes

- Emails are only used for you to follow up and are never shown to other
  visitors.
