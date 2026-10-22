# Parking Slots board

Open `/admin/parking-slots` after signing in as an administrator.

The page follows the supplied reference: white header/sidebar, yellow active navigation, location selector, four availability cards, search/type/status/floor filters, and color-coded cards grouped by floor. Sidebar branding and the parking photo reuse the editable Admin Login CMS assets. Administrator name, role and notifications come from the database.

Counts are actual slot rows for the selected location, not sample numbers from the reference. Each slot belongs to one summary category: accessible/disabled classifications take precedence, then EV vehicle/classification, then Car, Bike or Other. Locations containing other vehicle types show an additional Other summary card. Floor totals stay location-wide when filters narrow the visible cards, with a separate Showing count.

Ground/Ground Floor/Level 0 are displayed as Ground Floor; Level 1/First/Floor 1 as First Floor, and equivalent second-floor aliases as Second Floor. Other floor names are retained. Actual slot numbers are preserved.

Click a slot to edit its identifier, classification, price, floor, section, notes or status. Use the controls below the grid for single or batch creation. Duplicate identifiers in the same zone produce validation messages. Slots with booking history cannot be deleted; unbooked slots require confirmation before deletion.

The board refreshes from the database every 20 seconds while the page is visible and no dialog/filter interaction is active. Failed refreshes show a retry message. Location switching resets filters; Reset clears filters within the current location. Mobile layouts include collapsible navigation and two-column slot cards.

Validation:

```sh
php vendor/phpunit/phpunit/phpunit tests/Feature/ParkingSlotBoardTest.php
```

These tests use an isolated SQLite database and cover counts, category classification, floors, combined filters, location isolation, live refresh, creation/editing/batch operations, duplicate validation, deletion protection and empty states. Desktop/mobile browser checks verify no horizontal overflow, images, edit/add dialogs and JSON refresh. No operational slot data was inserted or changed for screenshots.
