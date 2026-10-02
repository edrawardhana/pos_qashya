# TABEL DOKUMENTASI GIT COMMIT - USE CASE BASIS

## USE CASE DIAGRAM SUMMARY

```
┌─────────────────────────────────────────────────────────────┐
│                   SYSTEM AKTOR (2)                          │
├─────────────────────────────────────────────────────────────┤
│ • ADMIN (Pengelola Sistem)                                  │
│ • KASIR (Operator Transaksi)                                │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│              ADMIN USE CASES (4)                            │
├─────────────────────────────────────────────────────────────┤
│ 1. Kelola Data Produk (CRUD)                                │
│ 2. Kelola Data Pengguna (CRUD + Roles)                      │
│ 3. Lihat Laporan Penjualan (Filter + Export)               │
│ 4. Lihat Riwayat Transaksi (Search + Detail)               │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│              KASIR USE CASES (3)                            │
├─────────────────────────────────────────────────────────────┤
│ 1. Transaksi Penjualan (Checkout + Receipt)                │
│ 2. Lihat Data Produk (Info + Stock)                        │
│ 3. Lihat Riwayat Transaksi (Search + Detail)               │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│          SHARED/BASE USE CASE (1)                           │
├─────────────────────────────────────────────────────────────┤
│ 1. Login/Logout (Authentication)                            │
└─────────────────────────────────────────────────────────────┘

TOTAL: 8 USE CASES
```

---

## ITERASI & GIT COMMIT MAPPING

### **ITERASI 1: SETUP & ADMIN FEATURES (MINGGU 1)**

```
┌────┬──────────────────────┬──────────────────┬─────────────────────────────┐
│ #  │ COMMIT HASH          │ USE CASE         │ FITUR UTAMA                 │
├────┼──────────────────────┼──────────────────┼─────────────────────────────┤
│ 1  │ init-auth-setup      │ Login/Logout     │ • Autentikasi               │
│    │                      │ (Base)           │ • Role-based Access         │
│    │                      │                  │ • Session Management        │
├────┼──────────────────────┼──────────────────┼─────────────────────────────┤
│ 2  │ feature-             │ Kelola Data      │ • Create Product            │
│    │ manage-product       │ Produk (Admin)   │ • Read/List Products        │
│    │                      │                  │ • Update Product            │
│    │                      │                  │ • Delete Product            │
├────┼──────────────────────┼──────────────────┼─────────────────────────────┤
│ 3  │ feature-manage-users │ Kelola Data      │ • Create User (Admin/Kasir) │
│    │                      │ Pengguna (Admin) │ • Assign Role               │
│    │                      │                  │ • Update User Info          │
│    │                      │                  │ • Delete User               │
└────┴──────────────────────┴──────────────────┴─────────────────────────────┘

HASIL ITERASI 1: Admin bisa login & manage produk & pengguna
```

---

### **ITERASI 2: TRANSAKSI (MINGGU 2)**

```
┌────┬──────────────────────┬──────────────────┬─────────────────────────────┐
│ #  │ COMMIT HASH          │ USE CASE         │ FITUR UTAMA                 │
├────┼──────────────────────┼──────────────────┼─────────────────────────────┤
│ 4  │ feature-sales-       │ Transaksi        │ • Search Produk             │
│    │ transaction          │ Penjualan        │ • Add to Cart               │
│    │                      │ (Kasir) +        │ • Set Quantity              │
│    │                      │ Lihat Data       │ • Auto Calculate Price      │
│    │                      │ Produk (Kasir)   │ • Checkout Process          │
│    │                      │                  │ • Print Receipt             │
├────┼──────────────────────┼──────────────────┼─────────────────────────────┤
│ 5  │ feature-stock-       │ Manajemen Stok   │ • Auto Deduct Stock         │
│    │ management           │ (implicit)       │ • Validate Stock Availability│
│    │                      │                  │ • Low Stock Alert           │
│    │                      │                  │ • Stock History Log         │
└────┴──────────────────────┴──────────────────┴─────────────────────────────┘

HASIL ITERASI 2: Kasir bisa melakukan transaksi penjualan dengan stok real-time
```

---

### **ITERASI 3: LAPORAN & FINALISASI (MINGGU 3)**

```
┌────┬──────────────────────┬──────────────────┬─────────────────────────────┐
│ #  │ COMMIT HASH          │ USE CASE         │ FITUR UTAMA                 │
├────┼──────────────────────┼──────────────────┼─────────────────────────────┤
│ 6  │ feature-sales-report │ Lihat Laporan    │ • Generate Report           │
│    │                      │ Penjualan (Admin)│ • Filter by Date Range      │
│    │                      │                  │ • Summary & Details         │
│    │                      │                  │ • Export PDF/Excel          │
├────┼──────────────────────┼──────────────────┼─────────────────────────────┤
│ 7  │ feature-transaction- │ Lihat Riwayat    │ • Transaction List          │
│    │ history              │ Transaksi        │ • Search by ID/Date         │
│    │                      │ (Admin & Kasir)  │ • View Detail               │
│    │                      │                  │ • Re-print Receipt          │
└────┴──────────────────────┴──────────────────┴─────────────────────────────┘

HASIL ITERASI 3: Admin bisa lihat laporan & history; Kasir bisa lihat history
```

---

## USE CASE TO COMMIT MAPPING

### **ADMIN USE CASES:**

| #   | Use Case                    | Commit # | Commit Hash                   | Status   |
| --- | --------------------------- | -------- | ----------------------------- | -------- |
| 1   | **Kelola Data Produk**      | #2       | `feature-manage-product`      | ✓ Iter 1 |
| 2   | **Kelola Data Pengguna**    | #3       | `feature-manage-users`        | ✓ Iter 1 |
| 3   | **Lihat Laporan Penjualan** | #6       | `feature-sales-report`        | ✓ Iter 3 |
| 4   | **Lihat Riwayat Transaksi** | #7       | `feature-transaction-history` | ✓ Iter 3 |

---

### **KASIR USE CASES:**

| #   | Use Case                    | Commit # | Commit Hash                   | Status   |
| --- | --------------------------- | -------- | ----------------------------- | -------- |
| 1   | **Transaksi Penjualan**     | #4       | `feature-sales-transaction`   | ✓ Iter 2 |
| 2   | **Lihat Data Produk**       | #4       | `feature-sales-transaction`   | ✓ Iter 2 |
| 3   | **Lihat Riwayat Transaksi** | #7       | `feature-transaction-history` | ✓ Iter 3 |

---

### **BASE USE CASE:**

| #   | Use Case         | Commit # | Commit Hash       | Status   |
| --- | ---------------- | -------- | ----------------- | -------- |
| 1   | **Login/Logout** | #1       | `init-auth-setup` | ✓ Iter 1 |

---

## COMMIT DETAIL PER ITERASI

### **ITERASI 1: MINGGU 1**

#### Commit #1: `init-auth-setup`

```
Use Case: Login/Logout (Base Feature)

File Changes:
├── database/migrations/
│   └── xxxx_create_users_table.php
├── app/Models/
│   └── User.php
├── app/Http/Controllers/
│   └── AuthController.php
├── resources/views/auth/
│   ├── login.blade.php
│   └── register.blade.php
└── config/auth.php

Key Features:
✓ User authentication
✓ Role assignment (Admin/Kasir)
✓ Session management
✓ Password encryption
```

---

#### Commit #2: `feature-manage-product`

```
Use Case: Kelola Data Produk (Admin)

File Changes:
├── database/migrations/
│   └── xxxx_create_products_table.php
├── app/Models/
│   └── Product.php
├── app/Http/Controllers/
│   └── ProductController.php
├── resources/views/products/
│   ├── index.blade.php
│   ├── create.blade.php
│   └── edit.blade.php
└── routes/web.php

Key Features:
✓ Create product (form validation)
✓ Read/List products
✓ Update product info
✓ Delete product
✓ Search & filter
```

---

#### Commit #3: `feature-manage-users`

```
Use Case: Kelola Data Pengguna (Admin)

File Changes:
├── database/migrations/
│   ├── xxxx_create_roles_table.php
│   └── xxxx_add_role_to_users_table.php
├── app/Models/
│   ├── User.php (updated)
│   └── Role.php
├── app/Http/Controllers/
│   └── UserController.php
├── resources/views/users/
│   ├── index.blade.php
│   ├── create.blade.php
│   └── edit.blade.php
└── app/Http/Middleware/
    └── CheckRole.php

Key Features:
✓ Create user (Admin/Kasir roles)
✓ Read user list
✓ Assign/update user role
✓ Delete user
✓ Role-based access middleware
```

---

### **ITERASI 2: MINGGU 2**

#### Commit #4: `feature-sales-transaction`

```
Use Case: Transaksi Penjualan (Kasir) + Lihat Data Produk (Kasir)

File Changes:
├── database/migrations/
│   ├── xxxx_create_transactions_table.php
│   └── xxxx_create_transaction_details_table.php
├── app/Models/
│   ├── Transaction.php
│   └── TransactionDetail.php
├── app/Http/Controllers/
│   └── TransactionController.php
├── resources/views/transactions/
│   ├── create.blade.php
│   ├── receipt.blade.php
│   └── cart.blade.php
├── app/Services/
│   └── CartService.php
└── resources/views/products/
    └── modal_select.blade.php

Key Features:
✓ Search & select product
✓ Add product to cart
✓ Adjust quantity
✓ Auto calculate total price
✓ Process checkout
✓ Generate & print receipt
✓ View product info (stock, price)
```

---

#### Commit #5: `feature-stock-management`

```
Use Case: Manajemen Stok (Auto-deduction)

File Changes:
├── database/migrations/
│   └── xxxx_create_stock_history_table.php
├── app/Models/
│   └── Stock.php
├── app/Services/
│   └── StockService.php
├── app/Http/Requests/
│   └── CheckStockRequest.php
└── app/Http/Controllers/
    └── TransactionController.php (updated)

Key Features:
✓ Auto deduct stock on transaction complete
✓ Validate stock availability before checkout
✓ Alert on low stock
✓ Track stock history/logs
```

---

### **ITERASI 3: MINGGU 3**

#### Commit #6: `feature-sales-report`

```
Use Case: Lihat Laporan Penjualan (Admin)

File Changes:
├── app/Http/Controllers/
│   └── ReportController.php
├── app/Services/
│   └── ReportService.php
├── resources/views/reports/
│   ├── sales.blade.php
│   └── export.blade.php
└── routes/web.php

Key Features:
✓ Generate sales report
✓ Filter by date range
✓ Summary (total sales, revenue)
✓ Detail breakdown (per product)
✓ Export to PDF/Excel
✓ Visual dashboard charts
```

---

#### Commit #7: `feature-transaction-history`

```
Use Case: Lihat Riwayat Transaksi (Admin & Kasir)

File Changes:
├── app/Http/Controllers/
│   └── HistoryController.php
├── app/Http/Requests/
│   └── FilterTransactionRequest.php
├── resources/views/history/
│   ├── transactions.blade.php
│   └── detail.blade.php
└── routes/web.php

Key Features:
✓ Display transaction list
✓ Role-based access (Admin: all, Kasir: own)
✓ Search by ID/date/product
✓ View transaction detail
✓ Re-print receipt
✓ Filter & pagination
```

---

## TIMELINE VISUAL

```
MINGGU 1 (ITERASI 1)
├── Commit #1: init-auth-setup
├── Commit #2: feature-manage-product
├── Commit #3: feature-manage-users
└── Testing & Retrospective

MINGGU 2 (ITERASI 2)
├── Commit #4: feature-sales-transaction
├── Commit #5: feature-stock-management
└── Testing & Retrospective

MINGGU 3 (ITERASI 3)
├── Commit #6: feature-sales-report
├── Commit #7: feature-transaction-history
└── Testing & Retrospective

TOTAL: 7 Commits (Lean & Focused)
```

---

## COMMIT STATISTIC

| Iterasi   | Minggu | Commits | Use Cases | Status         |
| --------- | ------ | ------- | --------- | -------------- |
| 1         | 1      | 3       | 3 UC      | ✓ Complete     |
| 2         | 2      | 2       | 2 UC      | ✓ Complete     |
| 3         | 3      | 2       | 3 UC      | ✓ Complete     |
| **TOTAL** | **3**  | **7**   | **8 UC**  | ✓ **ALL DONE** |

---

## TESTING CHECKLIST

### **Iterasi 1 Testing:**

- [ ] Commit #1: Login works, roles assigned correctly
- [ ] Commit #2: Product CRUD all working
- [ ] Commit #3: User CRUD & roles working

### **Iterasi 2 Testing:**

- [ ] Commit #4: Transaction checkout & receipt complete
- [ ] Commit #5: Stock auto-deducts, validation works

### **Iterasi 3 Testing:**

- [ ] Commit #6: Report generates, export works
- [ ] Commit #7: History searchable, detail displays

---

## QUICK REFERENCE

**Total Use Cases Implemented:** 8  
**Total Commits:** 7  
**Duration:** 3 weeks  
**Testing Method:** Black Box Testing + UAT  
**Code Version Control:** Git

---

**END OF DOCUMENTATION**
