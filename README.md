# Myrepo

A collection of web projects by Pranav Gowda N G: three PHP back-end applications and two browser-based tools built with plain HTML, CSS and JavaScript.

Projects
PHP applications

To-Do List · index.php A simple task manager. Add tasks, mark them complete, and remove them.

Blogging Platform · blogging.php A small blog for writing, editing and deleting posts, with clear confirmation and error messages.

Room Booking Management System · bookings.php Search for available rooms by date, make a booking, and cancel it later. Overlapping bookings for the same room are prevented.

Browser tools

Legito · Logito.html A calibration management system for weighing instruments, based on OIML R76. It covers the full journey of a calibration report: instrument registration, a guided test wizard with automatic pass or fail results, a four-stage approval workflow (Engineer, Reviewer, Approver), a searchable report archive with Word export, and a complete audit trail. Four user roles are supported.

Mail-mosaic · Code.html An email checker. Paste a message and it lists the links, addresses and domains it contains, highlights common scam phrases, and finds public IP addresses in the headers. It is an indicator only and cannot confirm whether an email is genuine.

Running the projects

Browser tools. Open Logito.html or Code.html in any modern browser. No installation is needed, and all data stays on your device.

PHP applications. Requires PHP 8 or later.

bash
git clone https://github.com/Pranav200611/Myrepo.git
cd Myrepo
php -S localhost:8000

Then visit http://localhost:8000.

Legito demo login. Use admin / admin123. Other roles: engineer / eng123, reviewer / rev123, approver / app123.

Notes

Legito is a learning project. It keeps its data in the browser and stores passwords as plain text, so please do not enter real credentials. Its tolerance table is a simplified setup and should be checked against the current OIML R76 edition before any formal use.

Author

Pranav Gowda N G Information Science & Engineering, Bapuji Institute of Engineering & Technology, Davangere .
