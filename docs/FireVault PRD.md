* \# Product Requirements Document: FireVault  
*   
* \*\*Free Fire ID Marketplace & Sales Management System\*\*  
*   
* Version 1.0 · Draft PRD  
*   
* \---  
*   
* \#\# 1\. Product Overview  
*   
* FireVault is a small, professional marketplace website for listing and selling Free Fire game IDs (accounts). Visitors browse listings and view account details, but all payment and handover happens manually and externally, over WhatsApp, with a moderator. Internally, FireVault also functions as a lightweight sales management system: an Admin (owner) manages moderators, listings, and revenue, while Moderators manage the listings assigned to them and record sales.  
*   
* FireVault is \*\*not\*\* a payment platform, \*\*not\*\* a buyer-account system, and \*\*not\*\* a live chat product. It is a catalog \+ CRM-style back office wrapped around a manual, trust-based WhatsApp sales process.  
*   
* \#\# 2\. Problem Statement  
*   
* Free Fire account sellers currently rely on scattered, ad-hoc channels — Facebook groups, Telegram channels, WhatsApp broadcast lists — to list accounts for sale. This creates several problems:  
*   
* \- No consistent, trustworthy presentation of account details (stats, screenshots, price).  
* \- No way to track which staff member is responsible for which listing or sale.  
* \- No visibility into total sales, revenue, or staff performance.  
* \- No structured safety information for buyers, which increases scam risk and erodes trust.  
* \- No internal system for assigning work, tracking todos, or communicating between owner and staff.  
*   
* FireVault solves this by giving the business a single branded storefront for listings and a simple internal dashboard for the people who run it.  
*   
* \#\# 3\. Product Vision  
*   
* FireVault should feel like a small, premium gaming marketplace — closer in spirit to a curated storefront than a generic classifieds site or admin panel. Buyers should trust what they see before they ever open WhatsApp. Staff should be able to run the day-to-day business — listing accounts, assigning them, closing sales — without friction, and the owner should have a clear, real-time picture of the business's health at any time.  
*   
* \#\# 4\. Goals  
*   
* \- Provide a polished public catalog of Free Fire IDs that builds buyer trust.  
* \- Make the WhatsApp handoff from listing to seller effortless and unambiguous.  
* \- Give the Admin full visibility and control over listings, moderators, and revenue.  
* \- Give Moderators a focused workspace for the listings and sales assigned to them.  
* \- Keep the sales record (actual price vs. listed price) accurate and auditable.  
* \- Preserve historical data (sales, activity) even when moderators are deactivated or reassigned.  
* \- Ship a small, coherent MVP rather than a sprawling platform.  
*   
* \#\# 5\. Non-Goals  
*   
* FireVault will explicitly \*\*not\*\* include, at least in this version:  
*   
* \- Online payment processing or any payment gateway integration.  
* \- Buyer accounts, registration, or login.  
* \- Shopping carts, checkout flows, or order management for buyers.  
* \- Subscriptions, memberships, or recurring billing.  
* \- Real-time chat (WhatsApp remains the channel of record).  
* \- Multi-vendor marketplace features (third-party sellers listing independently).  
* \- Auction/bidding mechanics.  
* \- Automated fraud detection or ID verification of Free Fire accounts.  
* \- Mobile native apps (a responsive web app covers all users).  
*   
* \#\# 6\. Target Users  
*   
* \- \*\*Buyers (public visitors):\*\* Free Fire players looking to buy a leveled-up account, rare skins, or a specific character/item loadout, who value seeing clear proof (screenshots, stats) before contacting a seller.  
* \- \*\*Admin (business owner):\*\* Runs the business, sets pricing strategy, manages staff, and needs an at-a-glance view of revenue and operations.  
* \- \*\*Moderators (staff/agents):\*\* Handle day-to-day listing creation, buyer conversations on WhatsApp, and closing sales; paid or evaluated in part on their sales activity.  
*   
* \#\# 7\. User Roles  
*   
* | Role | Access | Authentication |  
* | \--- | \--- | \--- |  
* | Visitor (Public) | Public website only, no login | None |  
* | Admin | Full system access | Required |  
* | Moderator | Scoped access to own listings, sales, todos, notices, messages | Required |  
*   
* Role permissions are enforced server-side, not just hidden in the UI. A Moderator account can never view another moderator's listings, sales, or internal admin data through the moderator dashboard, regardless of URL manipulation.  
*   
* \#\# 8\. User Stories  
*   
* \*\*Visitor\*\*  
*   
* \- As a visitor, I want to see featured/hot IDs on the homepage so I can quickly spot appealing accounts.  
* \- As a visitor, I want to filter and search listings so I can find an account matching my budget and preferences.  
* \- As a visitor, I want to see detailed account info and screenshots so I can trust what I'm buying.  
* \- As a visitor, I want a one-tap WhatsApp button so I can contact the right seller instantly.  
* \- As a visitor, I want to read how the buying process works and how to avoid scams so I feel safe transacting.  
*   
* \*\*Admin\*\*  
*   
* \- As the Admin, I want a dashboard summarizing listings, sales, and revenue so I can understand business health at a glance.  
* \- As the Admin, I want to add, edit, and reassign listings so I can manage inventory and staff workload.  
* \- As the Admin, I want to manage moderator accounts (add, activate/deactivate) so I can control who works for me.  
* \- As the Admin, I want to see revenue broken down by moderator so I can evaluate staff performance.  
* \- As the Admin, I want to send notices and assign todos to moderators so I can direct daily work.  
* \- As the Admin, I want to message moderators directly for anything that isn't a broadcast notice.  
* \- As the Admin, I want a log of important system activity so I have accountability and traceability.  
*   
* \*\*Moderator\*\*  
*   
* \- As a Moderator, I want to see only the listings and todos assigned to me so my workspace stays focused.  
* \- As a Moderator, I want to add and edit game IDs so I can list accounts I'm selling.  
* \- As a Moderator, I want to upload multiple screenshots per listing so buyers can trust the listing.  
* \- As a Moderator, I want to mark an ID as sold and enter the actual sale price so the sale is recorded accurately.  
* \- As a Moderator, I want to see my own sales history so I can track my performance.  
* \- As a Moderator, I want to message the Admin so I can ask questions or flag issues.  
*   
* \#\# 9\. Core User Journeys  
*   
* \*\*Journey A — Buyer purchase path\*\*  
*   
* 1\. Visitor lands on homepage, sees featured/hot IDs.  
* 2\. Visitor browses all listings, applies search/filters.  
* 3\. Visitor opens a listing's detail view (modal or page).  
* 4\. Visitor reviews stats, screenshots, price, and status.  
* 5\. Visitor reads "How Buying Works" and scam-prevention info (either beforehand or from the detail view).  
* 6\. Visitor clicks "Contact on WhatsApp," which opens WhatsApp pre-filled with the assigned moderator's number and a message referencing the listing.  
* 7\. Transaction and handover happen entirely on WhatsApp, outside FireVault.  
*   
* \*\*Journey B — Moderator closes a sale\*\*  
*   
* 1\. Moderator logs in, sees dashboard with assigned todos and notices.  
* 2\. Moderator adds a new game ID listing with details and screenshots.  
* 3\. A buyer contacts the moderator via WhatsApp (from the public listing) and completes the deal externally.  
* 4\. Moderator opens the listing, marks it "Sold," and enters the actual sale price.  
* 5\. System creates a sale record and updates the listing status; the listing disappears from public "available" views.  
* 6\. Moderator sees the sale reflected in their own sales history.  
*   
* \*\*Journey C — Admin oversight\*\*  
*   
* 1\. Admin logs in, views dashboard: totals, available/sold counts, monthly and total revenue.  
* 2\. Admin reviews sales history and revenue by moderator.  
* 3\. Admin manages listings: reassigns an ID from one moderator to another, marks an ID featured.  
* 4\. Admin manages moderators: adds a new moderator, deactivates an inactive one (their history remains intact, their active listings get reassigned).  
* 5\. Admin posts a notice to all moderators and assigns a todo to a specific moderator.  
* 6\. Admin reviews the system activity log for anything notable.  
*   
* \#\# 10\. Feature Requirements  
*   
* FireVault is organized into three functional layers:  
*   
* 1\. \*\*Public Website\*\* — browsing, discovery, and WhatsApp handoff (Section 11).  
* 2\. \*\*Admin Panel\*\* — business management and oversight (Section 12).  
* 3\. \*\*Moderator Panel\*\* — listing and sales operations (Section 13).  
*   
* All three share a single underlying data model (Section 14–17) covering game IDs, sales, and moderators.  
*   
* \#\# 11\. Public Website Requirements  
*   
* \- \*\*Homepage:\*\* hero/intro section, featured/hot listings carousel or grid, entry points into full catalog, and links to "How It Works," "Safety," and "Terms & Conditions."  
* \- \*\*Browse/Catalog page:\*\* paginated or infinite-scroll grid of all "Available" listings, each shown as a card with cover image, title, key stats (level, price), and featured badge if applicable.  
* \- \*\*Search & Filter:\*\* keyword search plus filters (see Section 20).  
* \- \*\*Listing Detail (modal or page):\*\* full game ID details, image gallery/screenshots, price, status, and a prominent WhatsApp CTA button.  
* \- \*\*WhatsApp CTA:\*\* clicking it opens WhatsApp (web or app) addressed to the assigned moderator's number, with a pre-filled message referencing the listing (see Section 16).  
* \- \*\*"How Buying Works" page:\*\* step-by-step explanation of the browse → contact → external transaction → handover flow.  
* \- \*\*"Safety / Scam Prevention" page:\*\* guidance on safe transacting (e.g., never pay before verifying, use recommended payment methods, confirm account access before final payment, report suspicious behavior).  
* \- \*\*Terms & Conditions page:\*\* standard marketplace terms, including a clear statement that FireVault does not process payments and is not a party to the transaction.  
* \- \*\*Sold/Hidden listings\*\* are not shown in public browse or search results.  
* \- Fully responsive across mobile, tablet, and desktop, since most buyers will arrive via mobile.  
*   
* \#\# 12\. Admin Requirements  
*   
* \- \*\*Dashboard:\*\* total listings, available listings, sold listings, total revenue, monthly revenue, at-a-glance recent activity.  
* \- \*\*Game ID management:\*\* full CRUD on all listings regardless of which moderator created them; assign/reassign to any moderator; toggle featured/hot flag; change status (Available/Sold/Hidden).  
* \- \*\*Sales history:\*\* full list of all sales across all moderators, filterable by date range and moderator, showing listed price vs. actual sale price.  
* \- \*\*Revenue by moderator:\*\* revenue and sale-count breakdown per moderator, over selectable time ranges.  
* \- \*\*Moderator management:\*\* add new moderator accounts, edit their details, activate/deactivate accounts. Deactivation disables login but preserves all historical data.  
* \- \*\*Moderator activity view:\*\* see a given moderator's listings, sales, and recent actions.  
* \- \*\*Notices:\*\* create notices visible to all moderators (or targeted moderators), with the ability to edit or remove them.  
* \- \*\*Todos:\*\* create and assign todo items to specific moderators; see completion status.  
* \- \*\*Messaging:\*\* send and receive direct messages with individual moderators (simple internal messaging, not real-time chat — see Section 18).  
* \- \*\*System activity log:\*\* chronological feed of important events (listing created/edited/reassigned, sale recorded, moderator activated/deactivated, etc.).  
*   
* \#\# 13\. Moderator Requirements  
*   
* \- \*\*Dashboard:\*\* summary of the moderator's own assigned todos, unread notices, active listings, and recent sales.  
* \- \*\*Todos & Notices:\*\* view todos assigned to them (and mark complete); view notices posted by Admin (read-only).  
* \- \*\*Game ID management (scoped):\*\* add new game IDs (auto-assigned to themselves, unless Admin reassigns later); edit and delete only the game IDs they are permitted to manage (their own, or others explicitly assigned to them by Admin).  
* \- \*\*Screenshots:\*\* upload, reorder, and remove screenshots on their own listings.  
* \- \*\*Mark as sold:\*\* change status to Sold and enter the actual sale price, generating a sale record.  
* \- \*\*Own sales history:\*\* view their own past sales, listed vs. actual price, and dates.  
* \- \*\*Messaging:\*\* send and receive direct messages with Admin only (not with other moderators).  
* \- \*\*Access boundaries:\*\* no access to other moderators' data, no access to admin revenue totals, moderator management, or system-wide activity logs.  
*   
* \#\# 14\. Game ID Management  
*   
* Each Game ID (listing) record includes:  
*   
* \- Title  
* \- UID (Free Fire account ID)  
* \- Level  
* \- Account age  
* \- Price (listed price)  
* \- Description (free text)  
* \- Character count  
* \- Gun skin count  
* \- Elite Pass count  
* \- Rare item count  
* \- Multiple screenshots (ordered gallery)  
* \- Status: Available / Sold / Hidden  
* \- Featured/hot flag (boolean)  
* \- Assigned moderator  
* \- Created by (which user created the record)  
* \- Created date  
* \- Updated date  
*   
* \*\*Status rules:\*\*  
*   
* \- \*\*Available\*\* — visible publicly, can be browsed, searched, contacted about.  
* \- \*\*Sold\*\* — not visible in public "available" listings or default search results; retained in the system with its sale record.  
* \- \*\*Hidden\*\* — admin/moderator can temporarily unpublish a listing (e.g., pending review, incomplete info) without deleting it; not visible publicly.  
*   
* \*\*Assignment rules:\*\*  
*   
* \- Every listing has exactly one assigned moderator at a time.  
* \- Only Admin can reassign a listing to a different moderator.  
* \- A moderator can create/edit/delete listings currently assigned to them; edit/delete permission on a listing follows the assignment, not just original authorship.  
*   
* \#\# 15\. Sales Management  
*   
* \- A sale record is created at the moment a listing is marked \*\*Sold\*\*.  
* \- The moderator who marks it sold must enter the \*\*actual sale price\*\* (may differ from listed price).  
* \- A sale record stores: game ID reference, moderator, listed price, actual sale price, sale date.  
* \- Once created, a sale record is the system's source of truth for revenue; it is not silently recalculated from the listing's price field.  
* \- Admin can view/filter all sales; Moderators can view only their own sales.  
* \- Correcting a sale (e.g., wrong price entered) should be an explicit edit action, ideally with a visible audit trail rather than a silent overwrite (see Section 22).  
*   
* \#\# 16\. WhatsApp Integration Flow  
*   
* \- Each moderator has a WhatsApp-capable phone number stored on their profile.  
* \- Each listing's "Contact on WhatsApp" button is generated using the \*\*currently assigned moderator's\*\* number — never a static number.  
* \- The button uses a standard \`wa.me\` (or equivalent) deep link with a pre-filled message template, e.g. referencing the listing title and/or UID, so the moderator immediately knows which account the buyer is asking about.  
* \- If a listing is reassigned, the WhatsApp button must automatically point to the new moderator on next page load — no manual link updates required.  
* \- FireVault does not track WhatsApp conversations, message delivery, or read receipts; the conversation happens entirely outside the system.  
* \- If a moderator's number is invalid or deactivated, the listing's contact button should not silently fail (see Section 23, Edge Cases).  
*   
* \#\# 17\. Revenue Tracking  
*   
* \- \*\*Revenue\*\* is always computed from \*\*actual sale price\*\*, never listed price.  
* \- \*\*Total revenue\*\* — sum of actual sale price across all sale records.  
* \- \*\*Monthly revenue\*\* — total revenue grouped by calendar month, viewable on the Admin dashboard (e.g., current month, and a recent-months trend).  
* \- \*\*Revenue by moderator\*\* — total revenue and sale count attributed to each moderator, based on who closed the sale.  
* \- Deactivating a moderator does not remove or reassign their historical revenue attribution.  
* \- Listed price vs. actual sale price variance may optionally be surfaced (e.g., average discount) as a secondary insight, but actual sale price is always the figure used for headline revenue numbers.  
*   
* \#\# 18\. Notices / Todos / Messaging  
*   
* FireVault includes three distinct, deliberately simple internal-communication tools:  
*   
* \- \*\*Notices (Admin → Moderators, broadcast):\*\* one-way announcements, e.g. "New pricing policy" or "Holiday hours." Can target all moderators or a specific subset. Moderators can read but not reply inline to a notice.  
* \- \*\*Todos (Admin → Moderator, assigned task):\*\* discrete action items assigned to a specific moderator with a description and a completion state (done/not done). Not a full project-management system — no subtasks, due-date reminders, or recurring tasks in MVP.  
* \- \*\*Messaging (Admin ↔ Moderator, direct):\*\* simple one-to-one message thread between Admin and each moderator, for anything that doesn't fit a notice or todo. Not moderator-to-moderator. Not real-time (standard page refresh/poll is sufficient — no WebSocket chat requirement for MVP).  
*   
* These three tools are intentionally kept separate rather than merged into one generic "inbox," since they serve different purposes (broadcast vs. task vs. conversation).  
*   
* \#\# 19\. Activity Tracking  
*   
* \- The system maintains an \*\*activity log\*\* of significant events, primarily for Admin visibility:  
*   \- Game ID created / edited / deleted / reassigned / status changed  
*   \- Sale recorded (with moderator and amount)  
*   \- Moderator account created / activated / deactivated  
*   \- Notices created  
*   \- Todos created / completed  
* \- Each log entry records: actor (who did it), action type, affected entity, and timestamp.  
* \- Admin can view the full activity feed; Moderators only implicitly "see" activity through their own dashboard (their own todos, sales, listings) — they do not get a system-wide log.  
* \- Activity records are retained even if the related moderator is later deactivated.  
*   
* \#\# 20\. Search and Filtering  
*   
* Public catalog search/filter capabilities:  
*   
* \- \*\*Keyword search\*\* — matches title, UID, and/or description.  
* \- \*\*Price range filter\*\* (min/max).  
* \- \*\*Level range filter\*\* (min/max).  
* \- \*\*Featured/hot toggle\*\* — show only featured listings.  
* \- \*\*Sort options\*\* — newest first, price low-to-high, price high-to-low, featured first.  
* \- Only \*\*Available\*\* listings appear in public search/browse results by default; Sold and Hidden are excluded.  
* \- Admin/Moderator dashboards have their own internal filters (by status, by moderator, by date range) separate from the public-facing search.  
*   
* \#\# 21\. Image Management  
*   
* \- Each listing supports \*\*multiple screenshots\*\*, uploaded by the Admin or the assigned Moderator.  
* \- Images are stored with an explicit order (so the gallery displays predictably, with a clear "cover image" as the first item).  
* \- Supported formats: standard web image formats (JPEG, PNG, WebP); reasonable file-size limits should be enforced to keep pages fast.  
* \- Uploaded images should be optimized/resized on upload (e.g., generate a web-friendly size) to keep the premium, fast-loading feel described in the Design Direction.  
* \- Moderators can only manage screenshots on listings they are permitted to edit; Admin can manage screenshots on any listing.  
* \- Deleting a listing removes its associated images from storage.  
*   
* \#\# 22\. Business Rules  
*   
* \- A listing always has exactly one assigned moderator; unassigned listings are not permitted.  
* \- Only Admin can create moderator accounts or change a listing's assigned moderator.  
* \- A Moderator can only edit/delete game IDs currently assigned to them.  
* \- Marking a listing "Sold" requires entering an actual sale price; it cannot be left blank or defaulted silently to the listed price.  
* \- Once "Sold," a listing is removed from public "Available" browsing/search but remains in the system for historical/reporting purposes.  
* \- Revenue figures are always derived from sale records (actual sale price), never from listing price fields.  
* \- Deactivating a moderator disables their login but does not delete or anonymize their historical listings, sales, or activity records.  
* \- Listings belonging to a deactivated moderator must be reassignable by Admin to an active moderator.  
* \- The public site never exposes moderator personal information beyond what's needed for the WhatsApp contact action (i.e., no moderator profile pages, emails, or internal notes visible publicly).  
*   
* \#\# 23\. Edge Cases  
*   
* \- \*\*Moderator deactivated while holding active listings:\*\* listings remain visible publicly (their status is unaffected by moderator status) but Admin should be prompted/able to reassign them to an active moderator so the WhatsApp button stays usable.  
* \- \*\*Moderator's WhatsApp number missing or invalid:\*\* the contact button should not be shown as if functional; the public UI should degrade gracefully (e.g., listing shows as temporarily unavailable for contact, or Admin is flagged to fix the number) rather than sending buyers to a dead link.  
* \- \*\*Listing reassigned mid-negotiation:\*\* since WhatsApp conversations happen outside FireVault, the system cannot track an "in-progress" conversation; reassignment simply changes which number future visitors see. This should be clearly understood as a limitation, not a bug.  
* \- \*\*Two moderators both think they're closing the same sale:\*\* the first one to mark it "Sold" wins; system should prevent double-marking (once Sold, the mark-as-sold action is no longer available to others).  
* \- \*\*Incorrect sale price entered:\*\* should be correctable via an explicit edit path (e.g., only Admin can amend a finalized sale record, or edits are logged) to avoid silent revenue discrepancies.  
* \- \*\*Listing with zero or incomplete data (no screenshots, missing price):\*\* should not be publishable to "Available" status until minimum required fields are present; Admin/Moderator sees a validation prompt.  
* \- \*\*Deleted moderator vs. deactivated moderator:\*\* the system should support deactivation (preserves history) as the standard path; hard-deletion of a moderator account (which would orphan historical records) should be avoided or heavily restricted.  
* \- \*\*Duplicate/near-duplicate listings:\*\* out of scope for automated detection in MVP; relies on Admin/Moderator diligence.  
*   
* \#\# 24\. MVP Scope  
*   
* \*\*Included in MVP:\*\*  
*   
* \- Public site: homepage, browse/catalog, search & filter, listing detail, WhatsApp CTA, How It Works, Safety page, Terms & Conditions.  
* \- Admin: dashboard, full game ID CRUD, moderator management (add/activate/deactivate/reassign), sales history, revenue totals (total \+ monthly \+ by moderator), notices, todos, direct messaging, activity log.  
* \- Moderator: dashboard, scoped game ID CRUD, screenshot management, mark-as-sold with actual price entry, own sales history, notices (read), todos (view/complete), direct messaging with Admin.  
* \- Core data model: Game ID, Sale, Moderator/Admin users, Notice, Todo, Message, Activity Log.  
*   
* \*\*Explicitly excluded from MVP (see Non-Goals):\*\*  
*   
* \- Payments/checkout, buyer accounts, carts, subscriptions, real-time chat, bidding, multi-vendor support, native mobile apps, automated fraud/ID verification.  
*   
* \#\# 25\. Future Improvements  
*   
* Potential post-MVP directions, not committed for v1:  
*   
* \- Buyer-side wishlist/favorites using lightweight local storage (still no buyer accounts).  
* \- Email or SMS notifications to Admin/Moderator for key events (new todo, notice, sale milestone).  
* \- Basic analytics (traffic sources, most-viewed listings, conversion from view → WhatsApp click).  
* \- Multiple WhatsApp numbers per moderator (e.g., separate business line) or WhatsApp Business API integration for tracked click-through.  
* \- More granular permission tiers (e.g., senior moderator vs. junior moderator).  
* \- Export of sales/revenue data (CSV) for accounting.  
* \- Public "recently sold" showcase (social proof) without exposing buyer identity.  
* \- Multi-language support.  
*   
* \#\# 26\. Success Criteria  
*   
* \- A visitor can go from homepage to a WhatsApp conversation with the correct moderator in 3 clicks or fewer.  
* \- Admin can determine total revenue, monthly revenue, and revenue-by-moderator without leaving the dashboard.  
* \- 100% of "Sold" listings have an associated sale record with an actual sale price.  
* \- Reassigning a listing updates its public WhatsApp contact target with no manual intervention.  
* \- Deactivating a moderator never results in data loss for that moderator's historical listings, sales, or activity.  
* \- Moderators cannot, through any path in the UI, view another moderator's listings, sales, or messages with Admin.  
* \- The public site loads and displays clearly on mobile devices, matching the premium/modern design direction (dark theme, Tailwind CSS, clean typography, no generic admin-template look).  
* 