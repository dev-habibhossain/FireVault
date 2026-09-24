\# FireVault — Software Project Requirements Document

\*\*Free Fire ID Marketplace & Sales Management System\*\* Stack: Laravel · Inertia.js · React · Tailwind CSS · MySQL Version 1.0 · Draft

\---

\#\# How to Read This Document

Every requirement has a unique ID, a short title, a description, a priority, the actor(s) it applies to, and acceptance criteria. Priorities follow MoSCoW:

\- \*\*Must\*\* — required for MVP; the product is not usable without it.  
\- \*\*Should\*\* — important, expected soon after MVP, but launch is not blocked by it.  
\- \*\*Nice\*\* — genuinely optional; cut freely if time is short.

ID prefixes: \`FR-PUB\` (public site), \`FR-AUTH\` (authentication), \`FR-ADM\` (admin), \`FR-MOD\` (moderator), \`FR-SALE\` (sales), \`FR-WA\` (WhatsApp), \`FR-MMGT\` (moderator management), \`FR-ACT\` (activity tracking), \`FR-NOT\` (notices), \`FR-TODO\` (todos), \`FR-MSG\` (messaging), \`NFR\` (non-functional).

\---

\#\# 1\. Functional Requirements — Public Website

| ID | Requirement | Priority | Actor | Description | Acceptance Criteria |  
| \--- | \--- | \--- | \--- | \--- | \--- |  
| FR-PUB-001 | Homepage | Must | Visitor | Landing page introducing FireVault, showing featured listings and entry points to browse, How It Works, Safety, and T\&C. | • Loads without login • Shows ≥1 featured listing block if any exist • Links to catalog, How It Works, Safety, T\&C |  
| FR-PUB-002 | Global navigation | Must | Visitor | Persistent header/nav with links to Home, Browse, How It Works, Safety, T\&C. | • Visible on all public pages • Responsive collapse to a mobile menu below 768px |  
| FR-PUB-003 | Featured/hot listings section | Must | Visitor | Homepage section showing only listings flagged \`featured \= true\` and \`status \= Available\`. | • Sold/Hidden/non-featured items never appear here • Empty state shown gracefully if no featured items exist |  
| FR-PUB-004 | Game ID catalog/browse page | Must | Visitor | Paginated grid of all \`Available\` listings, each as a card (cover image, title, level, price, featured badge). | • Sold and Hidden listings never appear • Pagination or infinite scroll works at 24+ items |  
| FR-PUB-005 | Search | Must | Visitor | Keyword search across title, UID, and description of \`Available\` listings. | • Returns matches on partial/case-insensitive input • Empty query returns full catalog • No results shows a clear empty state |  
| FR-PUB-006 | Filtering | Must | Visitor | Filter catalog by price range, level range, and featured-only toggle. | • Filters combine (AND logic) • Filters persist in the URL so results are shareable/bookmarkable |  
| FR-PUB-007 | Sorting | Should | Visitor | Sort catalog by newest, price ascending, price descending, featured-first. | • Selected sort persists across pagination within the same session |  
| FR-PUB-008 | Game ID detail view | Must | Visitor | Modal or dedicated page showing full listing detail: title, UID, level, account age, price, description, character/skin/pass/rare-item counts, screenshots, status. | • Accessible via a stable URL (deep-linkable) even if rendered as a modal • Sold/Hidden listings return a graceful "unavailable" state, not a 500 error |  
| FR-PUB-009 | Image gallery | Must | Visitor | Gallery/carousel of a listing's screenshots in stored order, with a clear cover image first. | • Supports at least 5 images without layout breakage • Touch-swipe works on mobile |  
| FR-PUB-010 | WhatsApp CTA | Must | Visitor | Prominent button on the detail view that opens WhatsApp addressed to the assigned moderator, pre-filled with a message referencing the listing. | • Link uses the moderator assigned \*\*at the time of click\*\*, not a cached value • Opens WhatsApp app on mobile, WhatsApp Web on desktop |  
| FR-PUB-011 | How It Works page | Must | Visitor | Static/CMS-lite page explaining Browse → View Details → Contact via WhatsApp → external transaction. | • Reachable from nav and footer on every public page |  
| FR-PUB-012 | Safety / Scam Prevention page | Must | Visitor | Static page with buyer safety guidance (verify before paying, safe payment habits, red flags). | • Linked from nav, footer, and ideally the listing detail view |  
| FR-PUB-013 | Terms & Conditions page | Must | Visitor | Static legal page stating FireVault is a listings/marketplace facilitator, not a payment processor or transaction party. | • Reachable from footer on every page |  
| FR-PUB-014 | Responsive layout | Must | Visitor | All public pages adapt cleanly to mobile, tablet, and desktop widths. | • No horizontal scroll at 320px width • Touch targets ≥44px on mobile |

\#\# 2\. Functional Requirements — Authentication

| ID | Requirement | Priority | Actor | Description | Acceptance Criteria |  
| \--- | \--- | \--- | \--- | \--- | \--- |  
| FR-AUTH-001 | Admin login | Must | Admin | Admin authenticates with email/username \+ password. | • Invalid credentials show a generic error (no user enumeration) • Successful login redirects to Admin dashboard |  
| FR-AUTH-002 | Moderator login | Must | Moderator | Moderator authenticates with email/username \+ password, using the same login form as Admin (role determined server-side). | • Deactivated moderator accounts cannot log in, and see a clear "account deactivated, contact admin" message rather than a generic failure |  
| FR-AUTH-003 | Logout | Must | Admin, Moderator | Authenticated users can end their session from any authenticated page. | • Session/token invalidated server-side • Redirects to public homepage or login page |  
| FR-AUTH-004 | No public registration | Must | Visitor | There is no sign-up flow anywhere on the public site. | • No route exists that creates a new Admin or Moderator account without an existing Admin performing the action |  
| FR-AUTH-005 | Role-based access control | Must | Admin, Moderator | Every authenticated route is gated by role; Moderators cannot reach Admin-only routes by URL manipulation. | • Direct navigation to an admin-only route as a moderator returns 403, not the admin view |  
| FR-AUTH-006 | Password reset | Should | Admin, Moderator | Authenticated users can reset a forgotten password via a secure, time-limited token flow (email-based). | • Reset tokens expire (e.g., 60 minutes) and are single-use |  
| FR-AUTH-007 | Session timeout | Should | Admin, Moderator | Idle sessions expire after a configurable period. | • Expired session redirects to login on next authenticated action |

\#\# 3\. Functional Requirements — Admin

| ID | Requirement | Priority | Actor | Description | Acceptance Criteria |  
| \--- | \--- | \--- | \--- | \--- | \--- |  
| FR-ADM-001 | Admin dashboard | Must | Admin | Overview screen showing total listings, available count, sold count, total revenue, and monthly revenue. | • Figures reflect live data (or a clearly timestamped cache) • Loads in a single request without N+1 query explosion |  
| FR-ADM-002 | Create game ID | Must | Admin | Admin can create a new listing with all fields defined in the data model, assigning it to any moderator. | • Required fields enforced (title, UID, price, status) • New listing defaults to \`Available\` unless set otherwise |  
| FR-ADM-003 | Edit game ID | Must | Admin | Admin can edit any listing, regardless of which moderator it's assigned to. | • Edits are timestamped (\`updated\_at\`) • Editing a Sold listing's price does not alter the historical sale record (see FR-SALE-006) |  
| FR-ADM-004 | Delete game ID | Must | Admin | Admin can delete a listing. | • Deleting a listing with an associated sale record is blocked or requires explicit confirmation (see Business Rules) • Associated images are removed from storage |  
| FR-ADM-005 | Assign / reassign game ID | Must | Admin | Admin can set or change the moderator assigned to any listing. | • Reassignment is logged in the activity log (actor, old moderator, new moderator, timestamp) • Public WhatsApp button reflects the new moderator immediately |  
| FR-ADM-006 | Set featured/hot status | Must | Admin | Admin can toggle a listing's featured flag. | • Only \`Available\` listings can be meaningfully featured (a Sold/Hidden listing being featured has no public effect) |  
| FR-ADM-007 | Change listing status | Must | Admin | Admin can set status to Available, Sold, or Hidden. | • Setting status to Sold outside the moderator "mark sold" flow still requires an actual sale price (see FR-SALE-001) |  
| FR-ADM-008 | Sales history (all) | Must | Admin | Admin can view every sale across all moderators, filterable by date range and moderator. | • Each row shows listed price, actual price, moderator, date • Exportable list is out of MVP scope (Nice) |  
| FR-ADM-009 | Total & monthly revenue | Must | Admin | Admin can view total revenue and revenue grouped by calendar month. | • Figures are computed from sale records' actual price, never listed price |  
| FR-ADM-010 | Revenue by moderator | Must | Admin | Admin can view revenue and sale count attributed to each moderator, over a selectable date range. | • Deactivated moderators still appear in historical breakdowns |  
| FR-ADM-011 | Create moderator account | Must | Admin | Admin can create a new moderator with name, contact info, WhatsApp number, and login credentials. | • WhatsApp number format is validated at creation |  
| FR-ADM-012 | Edit moderator | Must | Admin | Admin can edit a moderator's profile, including WhatsApp number. | • Changing the WhatsApp number takes effect on all of that moderator's listings immediately |  
| FR-ADM-013 | Activate / deactivate moderator | Must | Admin | Admin can toggle a moderator's active status without deleting the account. | • Deactivated moderator cannot log in • Deactivated moderator's historical data (listings, sales, activity) is preserved unchanged |  
| FR-ADM-014 | View moderator activity | Should | Admin | Admin can view a given moderator's listings, sales, and recent actions in one place. | • Includes at minimum: listings created, sales closed, last login |  
| FR-ADM-015 | Create notice | Must | Admin | Admin can create a notice visible to all moderators or a selected subset. | • See Section 9 (Notices) for full detail |  
| FR-ADM-016 | Create / assign todo | Must | Admin | Admin can create a todo assigned to a specific moderator. | • See Section 10 (Todos) for full detail |  
| FR-ADM-017 | Messaging with moderators | Must | Admin | Admin can send and read direct messages with any individual moderator. | • See Section 11 (Messaging) for full detail |  
| FR-ADM-018 | System activity feed | Should | Admin | Admin can view a chronological feed of significant system events across all users. | • See Section 8 (Activity Tracking) |

\#\# 4\. Functional Requirements — Moderator

| ID | Requirement | Priority | Actor | Description | Acceptance Criteria |  
| \--- | \--- | \--- | \--- | \--- | \--- |  
| FR-MOD-001 | Moderator dashboard | Must | Moderator | Overview of the moderator's own assigned todos, unread notices, active listings, and recent sales. | • Shows only data scoped to the logged-in moderator |  
| FR-MOD-002 | View own listings | Must | Moderator | Moderator can see all listings currently assigned to them, regardless of status. | • Sold/Hidden listings assigned to them are still visible in their own dashboard, unlike the public site |  
| FR-MOD-003 | Add game ID | Must | Moderator | Moderator can create a new listing, which is assigned to themselves by default. | • Required fields enforced identically to Admin creation (FR-ADM-002) |  
| FR-MOD-004 | Edit own/assigned game ID | Must | Moderator | Moderator can edit only listings currently assigned to them. | • Attempting to edit a listing assigned to another moderator returns 403 |  
| FR-MOD-005 | Delete own/assigned game ID | Must | Moderator | Moderator can delete only listings currently assigned to them, subject to the same sale-record protection as FR-ADM-004. | • Attempting to delete another moderator's listing returns 403 |  
| FR-MOD-006 | Upload/manage screenshots | Must | Moderator | Moderator can upload, reorder, and remove screenshots on their own listings. | • Upload respects file-type/size limits (see NFR-Security section) |  
| FR-MOD-007 | Mark listing as sold | Must | Moderator | Moderator can change status of their own listing to Sold. | • Action requires actual sale price input (see FR-SALE-001) • Cannot mark a listing already Sold as Sold again |  
| FR-MOD-008 | Enter actual sale price | Must | Moderator | When marking sold, moderator enters the real transaction price, which may differ from the listed price. | • Field is required and numeric • Value is stored on the sale record, not overwritten onto the original listing price |  
| FR-MOD-009 | View own sales history | Must | Moderator | Moderator can view their own past sales: listed price, actual price, date. | • Cannot view other moderators' sales through this screen |  
| FR-MOD-010 | View notices | Must | Moderator | Moderator can view notices addressed to them or to all moderators. | • Read-only; no reply-in-place |  
| FR-MOD-011 | View & complete todos | Must | Moderator | Moderator can view todos assigned to them and mark them complete. | • Cannot see todos assigned to other moderators |  
| FR-MOD-012 | Message admin | Must | Moderator | Moderator can send and read direct messages with Admin. | • Cannot message other moderators |

\*\*Moderators explicitly cannot:\*\*

\- View, edit, or delete listings assigned to other moderators.  
\- View another moderator's sales, revenue, or activity.  
\- View total business revenue, revenue-by-moderator breakdowns, or the system-wide activity feed.  
\- Create, edit, activate, or deactivate moderator accounts.  
\- Create or edit notices (view-only) or create todos (assignee-only, cannot self-assign new todos as an admin would).  
\- Message other moderators directly.  
\- Reassign a listing to a different moderator (assignment changes are Admin-only).

\#\# 5\. Functional Requirements — Sales

| ID | Requirement | Priority | Actor | Description | Acceptance Criteria |  
| \--- | \--- | \--- | \--- | \--- | \--- |  
| FR-SALE-001 | Mark as sold requires actual price | Must | Admin, Moderator | Transitioning a listing's status to Sold always requires an actual sale price value. | • System rejects a Sold status change with no price provided |  
| FR-SALE-002 | Sale record creation | Must | System | Marking a listing Sold creates an immutable-by-default sale record: game ID reference, moderator, listed price, actual price, sale date. | • Sale record persists even if the listing is later edited or deleted |  
| FR-SALE-003 | Sold date | Must | System | Sale date defaults to the server timestamp at the moment of the status change. | • Timezone-consistent across the app (store UTC, display local) |  
| FR-SALE-004 | Responsible moderator | Must | System | The sale record attributes the sale to the moderator who performed the "mark sold" action (or the assigned moderator, if Admin performs it on their behalf). | • Field is not nullable |  
| FR-SALE-005 | Sale history views | Must | Admin, Moderator | Admin sees all sales; Moderator sees only their own (see FR-ADM-008, FR-MOD-009). | — |  
| FR-SALE-006 | Revenue calculation | Must | System | All revenue figures (total, monthly, by moderator) sum \*\*actual sale price\*\* from sale records, never the listing's listed price. | • Editing a listing's listed price after sale never changes historical revenue figures |  
| FR-SALE-007 | Public visibility after sale | Must | System | Once a listing is marked Sold, it is excluded from public browse, search, and featured sections. | • Direct link to a sold listing's detail view shows a graceful "no longer available" state, not a 404 or crash |  
| FR-SALE-008 | Sale price correction | Should | Admin | Admin can correct an erroneous actual sale price on an existing sale record. | • Correction is logged in the activity feed with old and new values |

\#\# 6\. Functional Requirements — WhatsApp Integration

| ID | Requirement | Priority | Actor | Description | Acceptance Criteria |  
| \--- | \--- | \--- | \--- | \--- | \--- |  
| FR-WA-001 | Moderator WhatsApp number | Must | System | Every moderator profile stores one WhatsApp-capable phone number in a validated international format. | • Format validated on save (e.g., E.164) |  
| FR-WA-002 | Game ID → moderator relationship | Must | System | Every listing has exactly one currently-assigned moderator, from which its WhatsApp target is derived. | • No listing may exist with a null assigned moderator |  
| FR-WA-003 | WhatsApp redirect | Must | System | The public WhatsApp CTA generates a \`wa.me\`-style link using the assigned moderator's current number. | • Link is generated server-side/at render time, not hardcoded per listing |  
| FR-WA-004 | Pre-filled message | Should | System | The WhatsApp link includes a pre-filled message referencing the listing (title and/or UID) so the moderator has context immediately. | • Message is URL-encoded correctly and displays properly in WhatsApp |  
| FR-WA-005 | No moderator assigned | Must | System | This state should not occur (FR-WA-002), but if data is ever in this state, the public CTA must degrade gracefully rather than link to nothing. | • Button is hidden or replaced with a neutral "temporarily unavailable" indicator instead of a broken link |  
| FR-WA-006 | Assigned moderator inactive | Must | System | If the assigned moderator's account is deactivated, the public CTA must not silently point buyers to a dead contact. | • Admin is flagged (e.g., in dashboard or activity feed) to reassign • Public button shows an "unavailable, check back soon" state until reassigned |

\#\# 7\. Functional Requirements — Moderator Management

| ID | Requirement | Priority | Actor | Description | Acceptance Criteria |  
| \--- | \--- | \--- | \--- | \--- | \--- |  
| FR-MMGT-001 | Create moderator | Must | Admin | Admin creates moderator accounts; no self-registration exists. | Same as FR-ADM-011 |  
| FR-MMGT-002 | Edit moderator profile | Must | Admin | Admin edits name, contact, WhatsApp number, credentials. | Same as FR-ADM-012 |  
| FR-MMGT-003 | Activate / deactivate | Must | Admin | Toggle without deletion. | Same as FR-ADM-013 |  
| FR-MMGT-004 | Listing reassignment on deactivation | Must | Admin | When a moderator is deactivated, their currently \`Available\` listings should be flagged for reassignment. | • Admin dashboard surfaces a count/list of "listings needing reassignment" • Deactivation does not itself auto-delete or auto-hide the listings |  
| FR-MMGT-005 | Historical data preservation | Must | System | Deactivating (or, if ever supported, deleting) a moderator never deletes their sales, activity, or listing history. | • Foreign keys use soft-delete or nullable-with-preserved-name strategy, never cascading hard deletes on sale records |  
| FR-MMGT-006 | Access restriction enforcement | Must | System | All moderator-scoped endpoints enforce ownership server-side via Laravel Policies, not just UI hiding. | • Policy tests cover "moderator attempts to act on another moderator's resource" for every scoped action |

\#\# 8\. Functional Requirements — Activity Tracking

| ID | Requirement | Priority | Actor | Description | Acceptance Criteria |  
| \--- | \--- | \--- | \--- | \--- | \--- |  
| FR-ACT-001 | Login/logout events | Should | System | Log successful logins and logouts with actor and timestamp. | • Failed login attempts may optionally be logged for security review, without storing attempted passwords |  
| FR-ACT-002 | Listing lifecycle events | Must | System | Log create, update, delete, and status-change events on game IDs. | • Each entry records actor, listing reference, action type, timestamp |  
| FR-ACT-003 | Mark-sold events | Must | System | Log every sale creation as an activity entry. | • Entry references the sale record |  
| FR-ACT-004 | Assignment change events | Must | System | Log every reassignment of a listing, including old and new moderator. | — |  
| FR-ACT-005 | Admin account actions | Should | System | Log moderator creation, activation, and deactivation. | — |  
| FR-ACT-006 | Activity feed view | Should | Admin | Admin can view a paginated, filterable (by actor/date/type) activity feed. | • Not exposed to Moderators |  
| FR-ACT-007 | Scope discipline | Must | System | Activity tracking is limited to the events above; no keystroke, mouse, or granular page-view tracking is implemented. | • Confirmed absence of any such tracking in code review |

\#\# 9\. Functional Requirements — Notices

| ID | Requirement | Priority | Actor | Description | Acceptance Criteria |  
| \--- | \--- | \--- | \--- | \--- | \--- |  
| FR-NOT-001 | Create notice | Must | Admin | Admin creates a notice with a title, body, and audience (all moderators or selected moderators). | — |  
| FR-NOT-002 | Notice visibility | Must | System | A notice is visible only to its intended audience and to Admin. | • A moderator not in the target audience never sees the notice |  
| FR-NOT-003 | Priority level | Should | Admin | Notices can be tagged with a priority (e.g., Normal/Important) for visual emphasis. | • Higher-priority notices are visually distinguished (e.g., badge/color), not necessarily reordered |  
| FR-NOT-004 | Read/unread behavior | Should | Moderator | System tracks per-moderator read state for each notice. | • Moderator dashboard shows an unread count • Opening a notice marks it read |  
| FR-NOT-005 | Expiration | Nice | Admin | Admin can optionally set an expiry date after which a notice no longer appears on moderator dashboards. | • Expired notices remain in Admin's history but drop off moderator views |  
| FR-NOT-006 | Edit / remove notice | Should | Admin | Admin can edit or delete a notice after creation. | — |

\#\# 10\. Functional Requirements — Todos

| ID | Requirement | Priority | Actor | Description | Acceptance Criteria |  
| \--- | \--- | \--- | \--- | \--- | \--- |  
| FR-TODO-001 | Create todo | Must | Admin | Admin creates a todo with a description and assigns it to exactly one moderator. | — |  
| FR-TODO-002 | Priority | Should | Admin | Todo can be tagged Low/Medium/High priority. | — |  
| FR-TODO-003 | Due date | Should | Admin | Todo can optionally have a due date. | • Overdue todos are visually flagged on both Admin and Moderator views |  
| FR-TODO-004 | Status | Must | System | Todo has a status of Open or Done (Completed). | — |  
| FR-TODO-005 | Completion | Must | Moderator | The assigned moderator marks their own todo as complete. | • Admin can also mark it complete/reopen it |  
| FR-TODO-006 | Moderator visibility scope | Must | System | A moderator sees only todos assigned to them; Admin sees all todos across all moderators. | — |

\#\# 11\. Functional Requirements — Internal Messaging

| ID | Requirement | Priority | Actor | Description | Acceptance Criteria |  
| \--- | \--- | \--- | \--- | \--- | \--- |  
| FR-MSG-001 | Admin ↔ Moderator thread | Must | Admin, Moderator | Each moderator has exactly one message thread with Admin. | • No moderator-to-moderator threads exist |  
| FR-MSG-002 | Message fields | Must | System | Each message stores sender, receiver, body text, and timestamp. | — |  
| FR-MSG-003 | Read/unread state | Should | System | Messages track a read/unread state per recipient. | • Dashboard shows unread message count |  
| FR-MSG-004 | Delivery model | Must | System | Messages are delivered via standard request/response and page load or periodic polling; no WebSocket/real-time infrastructure is required for MVP. | • A new message appears on next page load/poll without requiring a persistent socket connection |  
| FR-MSG-005 | Access scope | Must | System | A moderator can only read/write their own thread with Admin; Admin can read/write any moderator's thread. | • Enforced via Laravel Policy, not just UI |

\---

\#\# 12\. Non-Functional Requirements

| ID | Category | Requirement |  
| \--- | \--- | \--- |  
| NFR-001 | Security | All authenticated routes require valid session/token; CSRF protection enabled on all state-changing requests (Laravel default). |  
| NFR-002 | Authentication | Passwords hashed with bcrypt/argon2 (Laravel default hashing); no plaintext password storage or logging. |  
| NFR-003 | Authorization | All role- and ownership-based access enforced via \*\*Laravel Policies/Gates\*\* at the controller/action layer, not merely hidden in the Inertia/React UI. |  
| NFR-004 | Validation | All incoming request data validated server-side via Laravel Form Requests (required fields, types, max lengths, numeric ranges for price/level). |  
| NFR-005 | File upload security | Screenshot uploads restricted by MIME type (JPEG/PNG/WebP) and max file size; files stored outside publicly-executable paths; filenames sanitized/randomized to prevent path traversal or overwrite attacks. |  
| NFR-006 | Performance | Public catalog and search pages should respond within \\\~300ms server-side processing time under normal load, using eager loading to avoid N+1 queries on listing → moderator → images relations. |  
| NFR-007 | Responsive design | All public and authenticated pages function and look intentional from 320px mobile width up to large desktop, per the Design Direction (dark theme, Tailwind CSS). |  
| NFR-008 | Accessibility | Public pages meet baseline accessibility: sufficient color contrast against the dark theme, semantic HTML, alt text on listing images, keyboard-navigable nav and forms. |  
| NFR-009 | Maintainability | Codebase follows Laravel conventions (Eloquent models, Form Requests, Policies, Resource controllers); React components organized by feature; consistent naming across FR IDs and code where practical. |  
| NFR-010 | Database integrity | Foreign keys enforced at the database level; sale records reference listings and moderators with constraints that prevent orphaned or duplicate sale rows for the same listing. |  
| NFR-011 | Error handling | User-facing errors (validation failures, 403s, 404s, "listing no longer available") are handled gracefully with clear messaging; no raw stack traces exposed in production. |  
| NFR-012 | SEO | Public pages (homepage, catalog, listing detail, How It Works, Safety, T\&C) have proper \`\<title\>\`, meta description, and Open Graph tags server-rendered via Inertia SSR or static meta injection, since these pages need to be crawlable and shareable. |  
| NFR-013 | Image optimization | Uploaded screenshots are resized/compressed to a reasonable web-delivery size on upload; lazy-loading used for gallery and catalog images. |  
| NFR-014 | Backup considerations | Regular automated MySQL backups (e.g., daily) and a documented restore procedure; uploaded images backed up alongside the database or stored on durable object storage. |  
| NFR-015 | Logging | Application errors logged server-side (Laravel log channels) separately from the business-facing activity log described in Section 8\. |

\---

\#\# 13\. Permission Matrix

\*\*Legend:\*\* ✅ \= can perform · ❌ \= cannot perform · 🔒 \= can perform, restricted to own/assigned resources only

| Action | Public | Admin | Moderator | Ownership Matters? |  
| \--- | \--- | \--- | \--- | \--- |  
| Browse/search public catalog | ✅ | ✅ | ✅ | No |  
| View listing detail (Available) | ✅ | ✅ | ✅ | No |  
| View listing detail (Sold/Hidden) | ❌ | ✅ | 🔒 (own/assigned only) | Yes |  
| Click WhatsApp CTA | ✅ | — | — | No |  
| Create game ID | ❌ | ✅ | ✅ | No (creates as self) |  
| Edit game ID | ❌ | ✅ | 🔒 | Yes |  
| Delete game ID | ❌ | ✅ | 🔒 | Yes |  
| Reassign game ID to another moderator | ❌ | ✅ | ❌ | — |  
| Set featured/hot flag | ❌ | ✅ | ❌ | — |  
| Change listing status (Available/Sold/Hidden) | ❌ | ✅ | 🔒 (own/assigned only) | Yes |  
| Mark listing sold \+ enter actual price | ❌ | ✅ | 🔒 | Yes |  
| Correct a sale record's price | ❌ | ✅ | ❌ | — |  
| View own sales history | ❌ | — | 🔒 (own only) | Yes |  
| View all sales / total revenue | ❌ | ✅ | ❌ | — |  
| View revenue by moderator | ❌ | ✅ | ❌ | — |  
| Create moderator account | ❌ | ✅ | ❌ | — |  
| Edit moderator account | ❌ | ✅ | ❌ | — |  
| Activate/deactivate moderator | ❌ | ✅ | ❌ | — |  
| View another moderator's activity | ❌ | ✅ | ❌ | — |  
| Create notice | ❌ | ✅ | ❌ | — |  
| View notice | ❌ | ✅ | 🔒 (if in audience) | Yes |  
| Create/assign todo | ❌ | ✅ | ❌ | — |  
| Complete own todo | ❌ | ✅ (any) | 🔒 (own only) | Yes |  
| Message Admin | ❌ | — | 🔒 (own thread only) | Yes |  
| Message a Moderator | ❌ | 🔒 (that moderator's thread) | ❌ | Yes |  
| Message another Moderator | ❌ | — | ❌ | — |  
| View system activity feed | ❌ | ✅ | ❌ | — |  
| Upload/manage screenshots | ❌ | ✅ | 🔒 | Yes |

\*\*Core principle:\*\* Admin has unrestricted access across all listings and moderators. Moderators are always scoped to resources currently assigned/owned by them; ownership is enforced server-side via Laravel Policies, never by UI visibility alone.

\---

\#\# 14\. Business Rules

1\. \*\*Available listings\*\* are the only listings visible in public browse, search, and featured sections.  
2\. \*\*Sold listings\*\* are immediately removed from public browse/search on status change; they remain visible in Admin's and the responsible moderator's own dashboards, alongside their sale record.  
3\. \*\*Hidden listings\*\* are never publicly visible but remain fully editable by Admin and the assigned moderator; used for temporary unpublishing without deletion.  
4\. \*\*Featured listings\*\* must also be \`Available\` to have any public effect; featuring a Sold or Hidden listing is allowed in the data model but has no visible outcome until it becomes Available again.  
5\. \*\*Assignment:\*\* every listing has exactly one assigned moderator at all times; a listing may never exist unassigned.  
6\. \*\*Reassignment\*\* is an Admin-only action and must be logged in the activity feed with old and new moderator.  
7\. \*\*Moderator deactivation\*\* disables login only; it never deletes, hides, or unassigns the moderator's existing listings automatically. Admin is responsible for reassigning any \`Available\` listings that need an active point of contact.  
8\. \*\*Sales\*\* are recorded via an immutable sale record created at the moment of the Sold transition; the record's actual price is authoritative for revenue and is never recalculated from the listing's listed price.  
9\. \*\*Revenue\*\* figures (total, monthly, by moderator) are always derived from sale records, never from listing price fields, and are unaffected by later edits to a sold listing's listed price.  
10\. \*\*Historical records\*\* (sales, activity log entries) are never deleted or reassigned when a moderator is deactivated; the moderator's name/reference remains intact for reporting accuracy.  
11\. \*\*Duplicate UID:\*\* the system should prevent two active (\`Available\` or \`Sold\`, non-deleted) listings from sharing the same UID, to avoid confusing duplicate listings of the same account; a validation error is raised on save.  
12\. \*\*Deleting sold listings:\*\* deletion of a listing that has an associated sale record should be restricted (e.g., blocked, or require explicit Admin confirmation with a warning), since deleting it must never delete the underlying sale/revenue record.  
13\. \*\*Editing sold listings:\*\* the listing's descriptive fields (title, description, images) may still be edited by Admin after sale for record-keeping accuracy, but such edits never alter the sale record's stored actual price or sale date.  
14\. \*\*Public visibility\*\* is governed strictly by \`status\`: only \`Available\` is public; \`Sold\` and \`Hidden\` are excluded from all public-facing queries, including search indexes and sitemaps.

\---

\#\# 15\. Requirements Summary by Priority

\*\*Must Have (MVP-blocking):\*\* FR-PUB-001–006, 008–014 · FR-AUTH-001–005 · FR-ADM-001–013, 015–017 · FR-MOD-001–012 · FR-SALE-001–007 · FR-WA-001–003, 005–006 · FR-MMGT-001–006 · FR-ACT-002–004, 007 · FR-NOT-001–002 · FR-TODO-001, 004–006 · FR-MSG-001–002, 004–005 · NFR-001–015 (all non-functional requirements are treated as Must, since they are foundational rather than optional features).

\*\*Should Have (early post-MVP or stretch-within-MVP):\*\* FR-PUB-007 · FR-AUTH-006–007 · FR-ADM-014, 018 · FR-SALE-008 · FR-WA-004 · FR-ACT-001, 005–006 · FR-NOT-003–004, 006 · FR-TODO-002–003 · FR-MSG-003.

\*\*Nice to Have:\*\* FR-NOT-005 (notice expiration), CSV export of sales/revenue (mentioned under FR-ADM-008), multiple WhatsApp numbers per moderator, and other items listed under Future Improvements in the PRD.

\---

\#\# 16\. Scope Discipline

This document intentionally excludes, in line with the project's constraints: payment gateway integration, buyer accounts/registration, shopping cart or checkout flows, subscriptions/recurring billing, real-time WebSocket chat, bidding/auction mechanics, multi-vendor support, and native mobile applications. Any future requirement in these areas should be raised as a new, explicitly-scoped addendum rather than folded into this MVP document.  
