# Career Canvas

Build a complete, production-quality React frontend UI for a modern corporate Job Portal / Careers website.

IMPORTANT:

This task is ONLY for the FRONTEND/UI.

Do NOT build WordPress.

Do NOT build PHP.

Do NOT build MySQL.

Do NOT build APIs.

Do NOT build backend authentication.

Do NOT integrate WP Job Manager.

Do NOT create real application submission APIs.

Use realistic MOCK DATA / local JSON data so every page and interaction works visually in the React frontend.

The final React application will later be integrated with a custom WordPress plugin/API.

The frontend must therefore be component-based, reusable, responsive and API-ready.

========================================================

1. DESIGN REFERENCES

========================================================

Use these only as UX references:

Netflix Jobs:

https://jobs.netflix.com/

WP Job Manager:

https://wpjobmanager.com/

Also use the provided screenshot as a visual reference for the job browsing experience.

IMPORTANT:

Do NOT clone Netflix.

Do NOT use Netflix logo.

Do NOT use Netflix branding.

Do NOT use Netflix colors.

Do NOT copy Netflix text.

Do NOT reproduce the screenshot pixel-for-pixel.

Take inspiration only from the GENERAL UX:

- Career landing page

- Job search

- Job filtering

- Job listing

- Selected job/detail experience

- Clear Apply button

- Job metadata

- Simple application journey

Create a completely ORIGINAL corporate careers website.

========================================================

2. DESIGN DIRECTION

========================================================

Use a LIGHT THEME ONLY.

The website should feel:

- Modern

- Premium

- Corporate

- Clean

- Professional

- Human

- Easy to navigate

- Recruitment-focused

- Enterprise-level

Do NOT use a dark theme.

Do NOT make it look like a generic Bootstrap template.

Do NOT make it look like a generic WordPress website.

Do NOT overuse gradients.

Do NOT overuse glassmorphism.

Do NOT use excessive animations.

Use generous whitespace.

Use clean typography.

Use subtle borders.

Use subtle shadows.

Use a professional brand accent color that can easily be replaced later.

The design should work for any company.

Do not hardcode the company name.

Use:

"Company Careers"

as the temporary brand name.

========================================================

3. TECHNOLOGY

========================================================

Use:

React

TypeScript

Vite

Tailwind CSS

Lucide React icons

Use modern React practices.

Create reusable components.

Avoid putting the entire application into one component.

Organize the project cleanly.

Suggested structure:

src/

components/

  layout/

  careers/

  jobs/

  filters/

  application/

  admin/

  common/

pages/

data/

hooks/

types/

utils/

assets/

Do not create unnecessary complexity.

========================================================

4. RESPONSIVE DESIGN

========================================================

The entire application must be fully responsive.

Desktop

Tablet

Mobile

Desktop should have:

Maximum content width around 1200–1400px.

Mobile should have:

single-column layouts.

Job filters should transform into a mobile filter drawer/bottom sheet.

Navigation should become a mobile menu.

Job detail should become a single-column layout.

Apply button should remain highly visible on mobile.

========================================================

5. GLOBAL HEADER

========================================================

Create a professional global header.

Desktop:

Logo / Company Careers

Navigation:

Careers

Teams

Locations

Life at Company

About Us

Right side:

Search icon

Optional:

"View Jobs"

The header should be sticky on scroll.

Use white background.

Subtle bottom border.

Mobile:

Logo

Hamburger

Mobile menu drawer.

Do NOT use Netflix logo.

========================================================

6. CAREERS HOME PAGE

========================================================

Route:

/careers

Create a beautiful careers landing page.

Hero:

Large heading:

"Find Your Next Opportunity"

Subheading:

"Explore open positions and find a role where you can make an impact."

Hero should contain a prominent job search interface.

Search fields:

[ Search by job title, keyword or skill ]

[ Location ]

[ Search Jobs ]

Use a clean large search bar.

Hero background should be light and customizable.

Do not use a dark hero.

========================================================

7. HOME PAGE SECTIONS

========================================================

After hero:

SECTION 1:

Featured Opportunities

Display 3–4 featured jobs.

Each card:

Job title

Department

Location

Work mode

Job type

Short description

Button:

View Job

Hover interaction.

--------------------------------------------------------

SECTION 2:

Explore Opportunities

Show large category cards:

Technology

Engineering

Marketing

Finance

Human Resources

Operations

Design

Product

Each category card:

Icon

Category name

Number of open positions

Example:

Technology

18 Open Positions

--------------------------------------------------------

SECTION 3:

Explore by Location

Cards:

Mumbai

Pune

Bengaluru

Delhi

Hyderabad

Remote

Show:

Location name

Number of open positions

--------------------------------------------------------

SECTION 4:

Why Join Us

A professional editorial section.

Use:

Large image placeholder

Text

3–4 benefits

Example:

Build meaningful products

Grow your career

Work with talented people

Make an impact

The user should be able to replace the content later.

--------------------------------------------------------

SECTION 5:

Latest Opportunities

Display latest 6 jobs.

Button:

View All Jobs

--------------------------------------------------------

SECTION 6:

CTA

"Ready to make your next move?"

Button:

Explore Open Positions

========================================================

8. ALL JOBS PAGE

========================================================

Route:

/careers/jobs

This is one of the most important screens.

Desktop layout:

LEFT:

Filter sidebar

RIGHT:

Job results

Top:

"Open Positions"

Short description.

Search bar.

Result count:

"48 Open Positions"

Sort:

Sort by:

Newest

Oldest

Title A–Z

========================================================

9. ADVANCED FILTER SIDEBAR

========================================================

Create a professional advanced filter sidebar.

Filters:

Keyword

Location

Department

Category

Job Type

Work Mode

Experience

Date Posted

Example:

FILTER JOBS

Search

Location

[ All Locations ]

Department

[ All Departments ]

Category

[ All Categories ]

Job Type

[ Full Time ]

Work Mode

[ Hybrid ]

Experience

[ 2–5 Years ]

Date Posted

[ Any Time ]

Buttons:

Apply Filters

Clear All

Use accordions for filters if appropriate.

Show selected filters as removable chips above results.

Example:

Technology ×

Mumbai ×

Hybrid ×

========================================================

10. MOBILE FILTER EXPERIENCE

========================================================

On mobile:

Do NOT show the large permanent sidebar.

Show:

[ Filters ]

button.

Click opens a filter drawer.

Drawer contains all filters.

Bottom of drawer:

[ Clear All ] [ Apply Filters ]

Show filter count:

Filters (3)

========================================================

11. JOB CARDS

========================================================

Create a reusable JobCard component.

Example:

---------------------------------------

Senior WordPress Developer

Technology · Engineering

Mumbai, India

Hybrid · Full Time

2–5 Years

Build and maintain scalable web platforms...

Posted 3 days ago

[ View Job ]

---------------------------------------

Optional:

Featured

badge.

Use subtle hover animation.

Do not make cards too tall.

Use clear visual hierarchy.

========================================================

12. JOB LIST STATES

========================================================

Create all states:

Normal

Loading

Empty

Error

Filtered

Search results

No results example:

"No opportunities found"

"Try changing your search criteria."

[ Clear Filters ]

Loading:

Use skeleton loaders.

Do NOT show blank white space.

========================================================

13. PAGINATION

========================================================

Desktop:

1 2 3 4 5 Next

Mobile:

Load More Jobs

Use mock pagination.

Structure the code so API pagination can replace it later.

========================================================

14. SINGLE JOB PAGE

========================================================

Route:

/careers/jobs/:slug

This page is extremely important.

Design it like a premium corporate job detail page.

Top section:

Back to all jobs

Job title:

Senior WordPress Developer

Metadata:

Technology

Mumbai, India

Hybrid

Full Time

Job ID:

JR40666

Posted:

3 days ago

Prominent:

[ APPLY NOW ]

========================================================

15. SINGLE JOB DESKTOP LAYOUT

========================================================

Desktop:

LEFT ~70%

Job Description

Responsibilities

Qualifications

Skills

Benefits

Additional Information

RIGHT ~30%

Sticky job summary card:

Job Overview

Location

Mumbai, India

Department

Technology

Job Type

Full Time

Work Mode

Hybrid

Experience

2–5 Years

Posted

3 days ago

Deadline

30 September 2026

[ APPLY NOW ]

The sidebar should remain sticky while scrolling.

========================================================

16. SINGLE JOB MOBILE

========================================================

On mobile:

Job title

Metadata

Description

Responsibilities

Qualifications

Benefits

Job overview

Then sticky bottom action:

[ APPLY NOW ]

The Apply button should remain easy to access.

========================================================

17. JOB DETAIL CONTENT

========================================================

Use realistic mock job content.

Example sections:

About the Role

Responsibilities

Qualifications & Experience

Required Skills

Good to Have

What We Offer

Additional Information

Do not use Lorem Ipsum.

Write realistic professional job content.

========================================================

18. JOB METADATA

========================================================

Create reusable:

JobMeta component.

Support:

Location

Department

Category

Job Type

Work Mode

Experience

Job ID

Posted Date

Deadline

Use Lucide icons.

========================================================

19. RELATED JOBS

========================================================

At bottom of job detail:

"You may also be interested in"

Show 3 related job cards.

Logic in mock data:

same department

same category

same location

========================================================

20. APPLY FLOW

========================================================

Clicking:

APPLY NOW

should open a professional application experience.

Preferred:

Slide-over panel OR dedicated application page.

For desktop:

Modal/side panel is acceptable.

For mobile:

Full-screen application experience.

Do NOT navigate to an external website.

========================================================

21. APPLICATION FORM

========================================================

Create:

ApplicationForm component.

Fields:

First Name *

Last Name *

Email *

Phone *

Current Location

Resume / CV *

Cover Letter

LinkedIn Profile

Portfolio Website

Years of Experience

Current Company

Notice Period

Privacy checkbox *

Submit:

[ SUBMIT APPLICATION ]

Keep the form simple.

Do not ask unnecessary personal information.

========================================================

22. RESUME UPLOAD UI

========================================================

Create a beautiful drag-and-drop resume upload component.

Example:

Upload your resume

Drag & drop your file here

or

[ Choose File ]

Accepted:

PDF, DOC, DOCX

Maximum:

5 MB

After selection:

resume.pdf

1.2 MB

[ Remove ]

Show upload progress visually using mock behavior.

No real server upload.

========================================================

23. FORM VALIDATION

========================================================

Frontend validation:

Required fields

Email validation

Phone validation

Resume required

File extension

File size

Privacy checkbox

Show errors inline.

Example:

Please enter a valid email address.

Do not use browser alert().

========================================================

24. APPLICATION SUCCESS

========================================================

After mock submission:

Show professional success screen.

Example:

Application Submitted Successfully

Thank you for applying for:

Senior WordPress Developer

Application ID:

APP-2026-00124

"Our recruitment team will review your application."

Buttons:

[ Back to Careers ]

[ View More Jobs ]

Use a success icon/illustration.

========================================================

25. CATEGORY PAGE

========================================================

Route:

/careers/category/:slug

Example:

/careers/category/technology

Header:

Technology Careers

"Explore opportunities across our technology teams."

Show:

18 Open Positions

Job filters

Job listing

Related categories

========================================================

26. DEPARTMENT PAGE

========================================================

Route:

/careers/department/:slug

Example:

/careers/department/engineering

Show:

Engineering

Description

Open positions

Filters

Job cards

========================================================

27. LOCATION PAGE

========================================================

Route:

/careers/location/:slug

Example:

/careers/location/mumbai

Show:

Jobs in Mumbai

Location description

Open positions

Job cards

========================================================

28. SEARCH RESULTS

========================================================

Route:

/careers/search

Example:

Search:

"WordPress"

Header:

Search results for "WordPress"

Show:

12 matching opportunities

Filters

Results

No results state.

========================================================

29. JOB TYPE PAGE

========================================================

Support:

/careers/job-type/full-time

Examples:

Full Time

Part Time

Contract

Internship

Show matching jobs.

========================================================

30. WORK MODE PAGE

========================================================

Support:

/careers/work-mode/remote

Examples:

Remote

Hybrid

Onsite

Show matching jobs.

========================================================

31. CAREERS ADMIN FRONTEND

========================================================

Create a completely separate frontend recruitment management UI.

IMPORTANT:

This is a UI prototype only.

Use mock authenticated recruiter state.

Do NOT implement real authentication.

Route:

/careers-admin

This UI will later be connected to WordPress capabilities.

Design it like a modern HR SaaS dashboard.

========================================================

32. ADMIN SIDEBAR

========================================================

Desktop sidebar:

Careers Admin

Overview

Jobs

Applications

Categories

Departments

Locations

Settings

Bottom:

View Careers

Logout

Mobile:

Sidebar becomes drawer.

========================================================

33. ADMIN DASHBOARD

========================================================

Route:

/careers-admin

Header:

Good morning

Careers Overview

Cards:

Total Jobs

48

Open Jobs

42

Applications

326

New Applications

24

Interviews

12

Hired

8

Use clean dashboard cards.

========================================================

34. ADMIN RECENT APPLICATIONS

========================================================

Table:

Candidate

Position

Location

Applied

Status

Action

Example:

Raj Kadam

Senior WordPress Developer

Mumbai

Today

New

View

Status badges:

New

Under Review

Shortlisted

Interview

Offer

Hired

Rejected

========================================================

35. ADMIN RECENT JOBS

========================================================

Table:

Job

Department

Location

Status

Applications

Published

Actions

Actions:

View

Edit

Duplicate

Applications

Close

========================================================

36. ADMIN JOBS PAGE

========================================================

Route:

/careers-admin/jobs

Header:

Jobs

Button:

+ Add New Job

Search:

Search jobs...

Filters:

Status

Department

Location

Table:

Job Title

Job ID

Department

Location

Type

Status

Applications

Date

Actions

Responsive mobile table should become cards.

========================================================

37. ADMIN ADD JOB

========================================================

Route:

/careers-admin/jobs/new

Create a professional job creation form.

Sections:

Basic Information

Job Title

Job ID

Department

Category

Location

Job Type

Work Mode

Experience

Job Description

Short Summary

Description

Responsibilities

Qualifications

Skills

Benefits

Application Settings

Application Deadline

Application Email

Featured Job

Buttons:

Save Draft

Publish Job

========================================================

38. ADMIN EDIT JOB

========================================================

Route:

/careers-admin/jobs/:id/edit

Same UI as Add Job.

Pre-populate mock data.

Buttons:

Save Changes

Publish

Close Job

Delete

========================================================

39. ADMIN APPLICATIONS

========================================================

Route:

/careers-admin/applications

Header:

Applications

Stats:

326 Total

24 New

18 Shortlisted

12 Interview

Search candidates.

Filters:

Status

Job

Department

Date

Table:

Application ID

Candidate

Job

Experience

Status

Applied

Action

========================================================

40. ADMIN APPLICATION DETAIL

========================================================

Route:

/careers-admin/applications/:id

Candidate header:

Raj Kadam

Senior WordPress Developer

Mumbai

Application ID:

APP-2026-00124

Sections:

Candidate Information

Email

Phone

Location

LinkedIn

Portfolio

Professional Information

Experience

Current Company

Notice Period

Resume:

[ View Resume ]

[ Download Resume ]

Cover Letter

Admin Notes

Status:

[ Under Review ▼ ]

Actions:

Shortlist

Move to Interview

Reject

Hire

Use confirmation dialogs where appropriate.

========================================================

41. ADMIN CATEGORIES

========================================================

Route:

/careers-admin/categories

Display:

Category

Jobs

Status

Actions

Button:

+ Add Category

Example:

Technology — 18 Jobs

Engineering — 12 Jobs

Marketing — 8 Jobs

========================================================

42. ADMIN DEPARTMENTS

========================================================

Route:

/careers-admin/departments

CRUD interface.

Fields:

Department Name

Description

Show:

Number of Jobs

========================================================

43. ADMIN LOCATIONS

========================================================

Route:

/careers-admin/locations

CRUD interface.

Examples:

Mumbai

Pune

Bengaluru

Delhi

Remote

========================================================

44. ADMIN SETTINGS

========================================================

Route:

/careers-admin/settings

Settings UI:

Company Name

Company Logo

HR Email

Application Notification Email

Jobs Per Page

Maximum Resume Size

Allowed Resume Types

Email notification toggles.

Privacy Policy

Save Settings.

========================================================

45. ADMIN RESPONSIVE DESIGN

========================================================

Desktop:

Sidebar + main content.

Tablet:

Compact sidebar.

Mobile:

Drawer navigation.

Tables:

Convert to cards on mobile when appropriate.

Forms:

One column on mobile.

========================================================

46. GLOBAL COMPONENTS

========================================================

Create reusable components:

Header

Footer

Button

Badge

Modal

Drawer

Toast

Pagination

SearchInput

Select

MultiSelect

Checkbox

FileUpload

JobCard

JobMeta

JobList

JobFilters

JobSearch

JobSkeleton

EmptyState

ErrorState

Breadcrumbs

SectionHeader

ConfirmDialog

Do NOT duplicate components.

========================================================

47. MOCK DATA

========================================================

Create realistic mock data.

At least:

30 jobs

10 departments/categories

10 locations

Multiple work modes

Multiple job types

Multiple experience levels

20+ applications

Use TypeScript interfaces.

Example:

Job:

{

 id,

 slug,

 title,

 jobId,

 department,

 category,

 location,

 country,

 workMode,

 jobType,

 experience,

 postedDate,

 deadline,

 featured,

 shortDescription,

 description,

 responsibilities,

 qualifications,

 skills,

 benefits

}

Application:

{

 id,

 candidateName,

 email,

 phone,

 jobId,

 jobTitle,

 experience,

 currentCompany,

 noticePeriod,

 status,

 appliedDate

}

========================================================

48. FILTER FUNCTIONALITY

========================================================

Even though there is no backend, filters must WORK using mock data.

Implement:

keyword search

location filter

department filter

category filter

job type filter

work mode filter

experience filter

date filter

Filters must combine.

Example:

Technology

+

Mumbai

+

Hybrid

should return only matching jobs.

========================================================

49. URL STATE

========================================================

Job filters should update URL query parameters.

Example:

/careers/jobs?department=technology&location=mumbai&workMode=hybrid

Search:

/careers/jobs?keyword=wordpress

This is important because the future WordPress API will use similar parameters.

========================================================

50. ROUTING

========================================================

Use React Router.

Routes:

/careers

/careers/jobs

/careers/jobs/:slug

/careers/category/:slug

/careers/department/:slug

/careers/location/:slug

/careers/job-type/:slug

/careers/work-mode/:slug

/careers/search

/careers/apply/:slug

Admin:

/careers-admin

/careers-admin/jobs

/careers-admin/jobs/new

/careers-admin/jobs/:id/edit

/careers-admin/applications

/careers-admin/applications/:id

/careers-admin/categories

/careers-admin/departments

/careers-admin/locations

/careers-admin/settings

========================================================

51. DESIGN TOKENS

========================================================

Create centralized design tokens.

Do not scatter colors throughout components.

Define:

primary

primaryHover

text

mutedText

background

surface

border

success

warning

error

Typography:

heading

body

small

Spacing:

xs

sm

md

lg

xl

2xl

Border radius:

sm

md

lg

This will make it easy to replace the design later.

========================================================

52. ANIMATIONS

========================================================

Use subtle animations only.

Examples:

Card hover

Button hover

Filter drawer

Modal

Page transitions

Skeleton loading

Avoid excessive animation.

The website should feel professional rather than flashy.

========================================================

53. ACCESSIBILITY

========================================================

Implement:

Keyboard navigation

Visible focus states

Proper labels

ARIA for modal/drawer

Accessible buttons

Accessible form errors

Color contrast

Screen-reader-friendly controls

========================================================

54. SEO-FRIENDLY FRONTEND STRUCTURE

========================================================

Use semantic HTML:

header

nav

main

section

article

aside

footer

Job title should use:

H1

Section headings:

H2

Do not create headings purely for visual sizing.

========================================================

55. FOOTER

========================================================

Create professional footer.

Columns:

Company

Careers

Teams

Locations

Resources

Social icons.

Legal:

Privacy

Terms

Cookie Policy

Bottom:

© 2026 Company. All rights reserved.

========================================================

56. IMPORTANT FUTURE WORDPRESS INTEGRATION

========================================================

The React application will later be connected to a custom WordPress plugin.

Therefore DO NOT couple components directly to mock data.

Create a clean data/service layer.

Example:

services/

    jobsService.ts

    applicationsService.ts

    categoriesService.ts

For now these services use mock JSON.

Later they can be replaced with:

WordPress REST API

without rewriting the UI components.

Example:

getJobs(filters)

getJob(slug)

getCategories()

getDepartments()

getLocations()

submitApplication(data)

getApplications()

createJob(data)

updateJob(id, data)

========================================================

57. API-READY ARCHITECTURE

========================================================

Create TypeScript interfaces for API responses.

Example:

Job

JobFilters

Application

Category

Department

Location

Pagination

Do not put fetch() calls directly inside visual components.

Use services/hooks.

========================================================

58. LOADING EXPERIENCE

========================================================

Every data-driven screen should support:

Loading

Success

Empty

Error

For example:

Job listing:

Skeleton cards

No jobs:

"No opportunities found"

Error:

"Unable to load jobs. Please try again."

========================================================

59. TOAST NOTIFICATIONS

========================================================

Use toast notifications for actions.

Examples:

"Job saved successfully"

"Job published successfully"

"Application submitted successfully"

"Application status updated"

"Job deleted"

Do not use browser alert().

========================================================

60. VISUAL QUALITY

========================================================

This must look like a real production corporate careers website.

Pay special attention to:

Typography hierarchy

Whitespace

Alignment

Card proportions

Filter usability

Button hierarchy

Form usability

Responsive behavior

Empty states

Loading states

Micro-interactions

Avoid:

Generic cards everywhere

Huge rounded corners

Too many colors

Excessive gradients

Dark backgrounds

Overly flashy animations

Crowded layouts

========================================================

61. IMPORTANT UI PRIORITY

========================================================

The most important pages are:

1. Careers Homepage

2. All Jobs + Advanced Filters

3. Single Job Detail

4. Application Form

5. Application Success

6. Careers Admin Dashboard

7. Admin Jobs

8. Admin Applications

Spend the most design effort on these.

========================================================

62. FINAL USER JOURNEY

========================================================

Candidate:

Careers Home

↓

Search Jobs

↓

Apply Filters

↓

Job Listing

↓

Open Job

↓

Read Job Details

↓

Apply Now

↓

Application Form

↓

Upload Resume

↓

Submit

↓

Success

Recruiter:

Careers Admin

↓

Dashboard

↓

Jobs

↓

Add/Edit Job

↓

Applications

↓

Candidate Detail

↓

Change Status

↓

Shortlist / Interview / Hire / Reject

========================================================

63. DO NOT BUILD BACKEND

========================================================

Again:

NO PHP.

NO WordPress.

NO MySQL.

NO Laravel.

NO Node backend.

NO authentication backend.

NO real email.

NO real resume storage.

NO external API.

Use mock data only.

========================================================

64. FINAL OUTPUT

========================================================

Generate the complete React frontend.

Make every page navigable.

Make all buttons and interactions work using mock data.

Make filtering functional.

Make search functional.

Make pagination functional.

Make application form functional using mock submission.

Make admin CRUD interactions work locally in React state.

Make responsive layouts.

Make all pages visually polished.

Do not stop at creating only the homepage.

Build the COMPLETE frontend experience.

This project was built with [Lovable](https://lovable.dev).

## Build with Lovable

Continue developing this project in the [Lovable editor](https://lovable.dev/projects/31e02e68-f863-4a13-a6f2-91df211f4a2b).

- **Ship faster**: describe what you want to build and Lovable handles the code.
- **Stay in sync**: every change made in Lovable is committed straight to this repository.
- **Full ownership**: this code is yours. Push to `main` on GitHub and your changes sync back into Lovable, ready for your next prompt.

## Development

Prefer working locally? You need Node.js and npm — [install with nvm](https://github.com/nvm-sh/nvm#installing-and-updating).

```sh
git clone <this-repository-url>
cd <repository-name>
npm i
npm run dev
```
