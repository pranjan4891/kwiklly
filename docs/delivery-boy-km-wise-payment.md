# Delivery Boy – KM-wise Payment

Delivery boy ko rakhene aur unhe **km-wise payment** dene ke liye jo structure add kiya gaya hai.

---

## 1. Kya add kiya gaya hai

### Tables / Columns

| Location | Kya add |
|----------|--------|
| **delivery_partners** (pehle se tha) | `phone`, `is_active` columns (optional) |
| **vendor_orders** | `delivery_distance_km` – is order ki delivery kitne km ki hai |
| **delivery_partner_payments** (nayi table) | Har order ke liye delivery boy ko kitna pay karna hai: `distance_km`, `rate_per_km`, `amount`, `status` (pending/paid), `paid_at` |

### Models

- **DeliveryPartner** – delivery boy / partner (name, type, charges_type, charges_value, phone, is_active).  
  `charges_type = 'distance_based'` aur `charges_value = rate per km` (e.g. 10 = ₹10/km).
- **DeliveryPartnerPayment** – har vendor_order ke liye ek record: kitni km, kitna rate, kitna amount, paid ya pending.

### Relationships

- **VendorOrder** → `deliveryPartner()`, `deliveryPartnerPayment()`
- **DeliveryPartner** → `vendorOrders()`, `payments()`

---

## 2. KM-wise payment kaise use karein

### Step 1: Delivery partner (delivery boy) add karein

**delivery_partners** table mein entry:

- `name` – delivery boy ka naam  
- `type` – `'own'` (in-house) / `'bluedart'` / `'dunzo'` / `'other'`  
- `charges_type` – **`'distance_based'`** (km-wise)  
- `charges_value` – **rate per km** (e.g. 15 = ₹15 per km)  
- `phone` – optional  
- `is_active` – 1

### Step 2: Order par delivery boy assign karein + distance (km) daalein

Jab order assign karein, **distance (km)** bhi save karein (manual ya baad mein map/distance API se):

```php
use App\Models\VendorOrder;
use App\Models\DeliveryPartner;
use App\Models\DeliveryPartnerPayment;

$vendorOrder = VendorOrder::find($id);
$deliveryBoy = DeliveryPartner::where('charges_type', 'distance_based')->first();

// Assign delivery boy and set distance (e.g. 5.2 km)
DeliveryPartnerPayment::createForOrder($vendorOrder, $deliveryBoy, 5.2);
```

Isse:

- `vendor_orders.delivery_partner_id` set hoga  
- `vendor_orders.delivery_distance_km` = 5.2  
- `delivery_partner_payments` mein record: distance_km = 5.2, rate_per_km = partner ka rate, amount = 5.2 × rate, status = pending  

### Step 3: Payout (delivery boy ko paisa mark karein)

**Pending amount** – delivery partner ke hisaab se:

```php
$partner = DeliveryPartner::find(1);
$pending = $partner->payments()->where('status', 'pending')->sum('amount');
$pendingOrders = $partner->payments()->where('status', 'pending')->with('vendorOrder')->get();
```

**Paid mark karna** (jab aap ne paisa de diya):

```php
$payment = DeliveryPartnerPayment::find($id);
$payment->markAsPaid('UTR123456'); // optional: payment reference
```

---

## 3. Distance (km) kaise aayega

- **Manual:** Vendor/Admin order detail par “Delivery distance (km)” field mein daal de.  
- **Auto (future):** Vendor lat/long + customer address lat/long se distance (e.g. Google Distance Matrix) calculate karke `delivery_distance_km` save kar sakte ho. Iske liye customer address mein lat/long hona chahiye.

---

## 4. Migration run karna

```bash
php artisan migrate
```

Isse `vendor_orders` mein `delivery_distance_km`, `delivery_partners` mein `phone`/`is_active` (agar nahi hai), aur `delivery_partner_payments` table create ho jayegi.

---

## 5. Admin / Vendor panel (optional next steps)

- Delivery partners list (CRUD) – add/edit delivery boy, rate per km set karna.  
- Order detail par: “Assign delivery boy” + “Delivery distance (km)” + Save → upar wala `createForOrder()` call.  
- “Delivery boy payouts” page: partner-wise pending amount, list of orders, “Mark as paid” + payment reference.

Yeh structure delivery boy ko km-wise payment dene ke liye kaafi hai; UI/controller aap apne admin/vendor panel ke hisaab se add kar sakte ho.
