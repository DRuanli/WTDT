# NOTE MANAGEMENT APPLICATION - WEB PROGRAMMING FINAL PROJECT

## README for Course 503073 - Final Project, Semester II/2024-2025

---

## Table of Contents
1. [Project Overview](#1-project-overview)
2. [Requirements Specification](#2-requirements-specification)
   - [Account Management](#21-account-management)
   - [Simple Note Management](#22-simple-note-management)
   - [Advanced Note Management](#23-advanced-note-management)
   - [Additional Requirements](#24-additional-requirements)
3. [Technical Implementation Guidelines](#3-technical-implementation-guidelines)
4. [Evaluation Criteria](#4-evaluation-criteria)
5. [Submission Requirements](#5-submission-requirements)
6. [Important Warnings](#6-important-warnings)

---

## 1. Project Overview

This project requires the development of a comprehensive note management web application that allows users to create, organize, edit, and share notes. The application must support various content formats including text, images, and attached files. The system will feature user authentication, note organization with labels, search functionality, collaboration features, and offline capabilities.

**Important Notice**: Features highlighted in red in the original document must be implemented exactly as described. Any deviation, modification, or addition of features beyond those specified will result in disqualification from evaluation.

---

## 2. Requirements Specification

### 2.1 Account Management

#### User Registration (0.25 points)
- Users must provide:
  - Email address
  - Display name
  - Password (entered twice for confirmation)
- Password storage must use bcrypt hashing (not plain text)
- Upon successful registration, users must be automatically logged in
- The system must send an activation email to the user's registered address

#### Account Activation (0.25 points)
- Before activation, users have full access to all features
- Unverified accounts must display a prominent notification advising users to check their email
- Clicking the activation link in the email must activate the account and remove the notification

#### User Login and Logout (0.25 points)
- Any attempt to access the website without being logged in must immediately redirect to the login interface
- After successful login, users must be directed to their personalized homepage showing their notes
- Include secure session management for maintaining authenticated state

#### Password Reset (0.25 points)
- Users must be able to request a password reset via email
- The system must either:
  - Send a link that users can click to reset their password, OR
  - Send an OTP that users must enter before being redirected to password reset
- After password reset, users must log in manually (no automatic login)

#### User Preferences (0.25 points)
- Users must be able to customize:
  - Font size of notes
  - Note colors
  - Toggle between light and dark themes
- Preferences must persist across sessions

#### View Profile and Avatar (0.25 points)
- Users must be able to view their profile information
- Include display of user avatar if implemented

#### Edit Profile and Avatar (0.25 points)
- Users must be able to edit their profile information
- Allow updating of avatar if implemented

#### Change Password (0.25 points)
- Users must be able to change their password while logged in
- Require current password verification for security

### 2.2 Simple Note Management

#### Display Notes in Different Views
- **Grid View (0.25 points)**:
  - Must be the default view
  - Notes arranged in a grid layout
  - Must properly display note titles, partial content, and note status icons

- **List View (0.25 points)**:
  - Alternative view that users can switch to
  - Notes arranged in a vertical list
  - Must display same information as grid view in a different layout

#### Note Management Core Functions
- **Create Notes (0.25 points)**:
  - Users must only input title and content fields
  - No additional fields or attributes are permitted
  - Must use the same interface for both creating and editing notes

- **Update Notes (0.25 points)**:
  - Must use the same interface as note creation
  - Changes must be automatically saved without requiring a "save" button
  - Auto-save must work reliably to prevent data loss

- **Delete Notes (0.25 points)**:
  - A confirmation dialog must always be displayed before deletion
  - The dialog must require explicit user consent to proceed

- **Auto-save Notes (0.25 points)**:
  - Note content must be saved automatically as users type
  - No "save" button should be present
  - Should implement with appropriate timing to balance performance and data security

#### Note Enhancement Features
- **Attach Images to Notes (0.25 points)**:
  - Support for one or multiple images per note
  - Implementation details at developer's discretion, but must be user-friendly

- **Pin Notes to Top (0.25 points)**:
  - Allow users to "pin" specific notes to appear at the top
  - Multiple pinned notes must be arranged by pin time
  - Must provide a visual indicator for pinned notes

- **Search Notes (0.25 points)**:
  - Implement live search functionality (no search button)
  - Search must scan both title and content of notes
  - Include a 300ms delay for performance optimization
  - Results should update as the user types

#### Note Organization System
- **Label Management (0.25 points)**:
  - Users must be able to:
    - View a list of all labels
    - Add new labels
    - Rename existing labels
    - Delete labels

- **Attach Labels to Notes (0.25 points)**:
  - Notes can be label-free or have one or multiple labels
  - Interface must allow easy association/disassociation of labels
  - Labels must be visually indicated on notes

- **Filter Notes by Labels (0.25 points)**:
  - Users must be able to filter notes to display only those with selected labels
  - Filter controls must be intuitive and accessible
  - If a label is deleted, it should not affect the notes
  - If a label is renamed, all associated notes must automatically reflect the new name

### 2.3 Advanced Note Management

#### Security Features
- **Enable and Disable Password on Notes (0.5 points)**:
  - Allow setting a unique password for individual notes
  - Each note must have its own password unrelated to other notes
  - Provide UI controls to enable/disable password protection

- **Password Protection Management (0.5 points)**:
  - When password protection is enabled:
    - Users must be prompted for the password before viewing, editing, or deleting
    - Users must be able to change a note's password
    - Users must be able to disable password protection
  - Better implementation:
    - Require double entry of passwords when creating/changing
    - Require current password verification before changing or disabling

#### Collaboration Features
- **Share and Receive Notes (0.5 points)**:
  - Allow users to share notes with other registered users
  - Owner must be able to:
    - Specify permissions (read-only or edit rights)
    - Select one or multiple recipients
    - Revoke access or modify sharing settings
  - Better implementation:
    - Validate recipient emails against registered users
    - Send email notifications to recipients
    - Display prominent notifications to recipients on login

- **Collaboration and Real-time Modification (0.5 points)**:
  - Recipients must have a dedicated section showing notes shared with them
  - For each shared note, clearly indicate:
    - Sharing status (read-only or editable)
    - Who shared the note
    - When sharing occurred
  - For notes with edit permissions, implement WebSocket-based real-time collaboration:
    - Multiple users can edit simultaneously
    - Changes are visible to all editors in real time

#### Visual Indicators
- Special notes (shared, pinned, password-protected) must display recognizable icons
- Icons must be visible in both list and grid views
- Users should be able to immediately identify note status without opening it

### 2.4 Additional Requirements

#### UI and UX (0.5 points)
- User interface must be visually appealing
- User experience must be intuitive and efficient
- Points awarded based on:
  - Level 1 (0 points): Poor design, inconsistent UI, no feedback, no accessibility
  - Level 2 (partial points): Basic styling, inconsistent UX, limited feedback
  - Level 3 (full points): Polished UI, intuitive UX, comprehensive feedback, fully accessible

#### Responsive Design (0.5 points)
- Application must adapt seamlessly to different screen sizes
- Must function well on:
  - Smartphones
  - Tablets
  - Desktop monitors
- Points awarded based on:
  - Level 1 (0 points): No adaptation to screen sizes, misaligned elements
  - Level 2 (partial points): Partially responsive, layout issues on some devices
  - Level 3 (full points): Fully responsive, optimized layouts, proper scaling

#### Offline Capabilities (0.5 points)
- Implement as a Progressive Web App (PWA)
- Allow viewing note content when offline
- Implement local database using JavaScript
- Synchronize data when connection is restored
- Points awarded based on:
  - Level 1 (0 points): No offline functionality, no caching
  - Level 2 (partial points): Limited offline access, unreliable service workers
  - Level 3 (full points): Robust offline access, effective caching, proper data synchronization

#### Online Deployment (0.5 points)
- Host the website and database on a public platform
- Make accessible via a domain name
- Ensure system runs without errors during grading period
- Alternative: Use docker-compose following instructor guidelines
- Points awarded based on:
  - Level 1 (0 points): Not deployed, no hosting
  - Level 2 (partial points): Online but unstable, poor performance
  - Level 3 (full points): Fully deployed, secure (HTTPS), optimized for performance

---

## 3. Technical Implementation Guidelines

### Allowed Technologies
- Frontend: Any libraries or frameworks (e.g., Bootstrap, React)
- Backend: Any frameworks (e.g., Laravel)

### Implementation Notes
- A feature is considered fully completed only if it:
  - Functions as described
  - Includes error handling
  - Avoids critical security vulnerabilities
  - Adheres to industry best practices

### Coding Guidelines
- If not using Docker Compose or public deployment:
  - Use relative URLs instead of hardcoding (e.g., use `images/phone.jpg` instead of `http://localhost:8080/images/phone.jpg`)
  - Don't place project in subdirectories (serve directly at `http://localhost:8080/` not `http://localhost:8080/final_project/`)
  - For XAMPP, store files in `htdocs` directory, not in subfolders like `htdocs/final_project`
  - Don't hardcode port numbers in source code

- If using Docker Compose:
  - Include all necessary modules and configuration
  - Provide clear setup instructions

---

## 4. Evaluation Criteria

The project will be evaluated based on a 28-point rubric divided into four main sections:

1. **Account Management** (2.0 points)
   - 8 features worth 0.25 points each

2. **Simple Note Management** (4.0 points)
   - 12 features worth 0.25-0.5 points each

3. **Advanced Note Management** (2.0 points)
   - 4 features worth 0.5 points each

4. **Additional Requirements** (2.0 points)
   - 4 features worth 0.5 points each

Each feature will be graded on a three-level scale:
- **Level 1 (0 points)**: Feature not available or not working
- **Level 2 (25-75% of points)**: Feature implemented but with issues
- **Level 3 (full points)**: Feature correctly implemented with minimal bugs

Total possible score: 10 points

---

## 5. Submission Requirements

### Required Components
1. **Rubrik.docx**
   - Self-assessment of all 28 features
   - Include public URL and login credentials
   - Will be provided by instructor at submission time

2. **Source Folder**
   - If not using Docker: Complete source code (frontend, backend, database)
   - If using Docker: Source code, docker-compose file, and setup instructions
   - Must be "cleaned" to remove unnecessary files

3. **Demo Video (demo.mp4)**
   - Must include all team members
   - Brief overview of technologies and architecture
   - Sequential demonstration of all 28 features
   - Minimum 1080p resolution with clear audio
   - Upload to YouTube if file size is too large (include link)

4. **Readme.txt**
   - Building and running instructions
   - URL and server login information (if applicable)
   - Usernames/passwords for test accounts
   - Any relevant notes for evaluation

### Submission Process
1. Organize all components into a folder named `id1_fullname1_id2_fullname2`
2. Compress folder into a ZIP file with the same name
3. Submit through the online learning system (elearning)
4. Only elearning submissions are accepted (no email)

---

## 6. Important Warnings

### Critical Rules
- Failure to submit source code, video, or rubrik.docx will result in a score of 0
- Submitting an unrelated project will result in a score of 0
- Code sharing between groups is prohibited
- Using source code from the internet is prohibited
- Similar code across groups will result in a score of 0 for all involved

### Potential Deductions
- Late submission: -1 point per day (even 1 second late counts as 1 day)
- Complex project without clear setup instructions: -2 points
- Unclean project with unnecessary files: -0.5 point
- Missing information for grading: -1.0 point

### Final Note
For questions or concerns, contact the instructor directly or via email at maivanmanh@tdtu.edu.vn.

---

*Last updated: April 10, 2025*# Web
