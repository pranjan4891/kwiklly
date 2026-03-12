# Customer Address – Lat/Long (Applied)

## Implemented

### 1. Database
- **Migration** `2026_02_04_120000_add_latitude_longitude_to_customer_addresses.php`: `customer_addresses` table mein **latitude**, **longitude** (nullable decimal) columns add.

### 2. CustomerAddress model
- **Fillable:** `latitude`, `longitude` add.
- **Casts:** `latitude`, `longitude` → decimal.
- **Relation:** `user()` – address belongs to **User** (customer).
- **Methods (vendor/branch ke sath relation):**
  - `isDeliverableByVendor(int $vendorId): bool` – is address (lat/long) us vendor/branch ke kisi bhi `delivery_locations` polygon ke andar hai ya nahi.
  - `isDeliverableBy(VendorAdmin $vendorOrBranch): bool` – same check using VendorAdmin instance.

### 3. VendorAdmin (vendor / branch) model
- **Relation:** `deliveryLocations()` – hasMany DeliveryLocation.
- **Method:** `canDeliverToAddress(CustomerAddress $address): bool` – kya ye vendor/branch is address par deliver kar sakta hai (address lat/long vs is vendor ke delivery polygons).

### 4. Controllers
- **AddressController** (store): `latitude`, `longitude` validate + save.
- **AddressController** (update): `latitude`, `longitude` in `only()` list.
- **CustomerController** (updateAddress): validation mein `latitude`, `longitude` (nullable|numeric).

### 5. My Account – Address modals
- **Add Address form:** hidden inputs `latitude`, `longitude`; Google Places `place_changed` par `place.geometry.location` se lat/lng set; save par bhejein.
- **Edit Address form:** hidden inputs `latitude`, `longitude`; edit open par autocomplete init; place select par lat/lng set; load address par existing lat/lng populate.
- **resetAddAddressForm:** lat/lng clear.

---

## Relations summary

| Entity        | Relation / usage |
|---------------|-------------------|
| **User**      | CustomerAddress `user_id` → User (address belongs to user). |
| **Vendor**    | VendorAdmin (vendor) has `deliveryLocations()`; `canDeliverToAddress($address)` se check: address lat/long in vendor’s delivery polygon? |
| **Branch**    | Same as vendor – VendorAdmin (branch) has `deliveryLocations()`; `canDeliverToAddress($address)` se check. |

Usage in code:
- `$address->isDeliverableByVendor($vendorId);`
- `$address->isDeliverableBy($vendorOrBranch);`
- `$vendorOrBranch->canDeliverToAddress($address);`

---

## Checkout / vendor list (next step)

Jab user saved address select kare, har vendor/branch ke liye:
- `$address->isDeliverableBy($vendor)` ya `$vendor->canDeliverToAddress($address)` call karein.
- Andar ho to “Delivery available”, bahar ho to “Service not available at this address”.
