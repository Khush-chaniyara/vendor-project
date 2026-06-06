# VendorBridge: Procurement Management System

VendorBridge is a comprehensive ERP-based Procurement Management System.

🚀 Key Features
Admin Portal: User & Role management, system-wide analytics, and vendor oversight.

Manager Portal: Approval workflows for submitted quotes and automated PO generation.

Vendor Portal: RFQ viewing, competitive quotation submission, and status tracking.

PO portal:create and manage the Request for Quotation and see POs and Invoices.

Real-time Analytics: Bar-chart driven dashboard for monitoring RFQs, POs, and financial health.

🛠 Tech Stack
Frontend: HTML5, CSS3, Bootstrap 5.3, Chart.js.

Backend: PHP 8.x.

Database: MySQL.

How To Run Code :
Clone the repository into your htdocs (XAMPP) or www (WAMP) folder.

Database Setup: - Create a database named vendorBridge.

Import the provided database.sql files to create the schema and initial data.

Configuration: - Edit config.php to update your database credentials (DB_HOST, DB_USER, DB_PASS).

Access:

Open your browser and go to :
```bash
http://localhost/vendorbridge.
```

Demo Credentials :-
```
Admin : 
  email:admin@vendorbridge.com
  password:admin123
manager :
  email:manager@vendorbridge.com
  password:manager123
procurement:
  email:procurement@vendorbridge.com
  password:procurement123
vendor:
  email:hiten@vendorbridge.com
  password:hiten123
```

📂 Project Structure

/admin/: Dashboard for system administrators.

/manager/: Procurement management and approval interface.

/vendor/: Portal for supplier quotation submissions.

/procurement/:create and manage the Request for Quotation and see POs and Invoices.

/config.php: Central database connection and security configuration.
