<?php
include 'db_connection.php';
?>

<?php include 'components/admin/admin_header.php'; ?>

<style>
    .customer-table {
        width: 90%;
        margin: 24px auto;
        border-collapse: collapse;
        font-size: 15px;
        color: #334155;
    }

    .customer-table th, .customer-table td {
        border: 1px solid #e5e7eb;
        padding: 12px 16px;
        text-align: left;
    }

    .customer-table th {
        background-color: #f1f5f9;
        font-weight: 600;
    }

    .customer-table tr:nth-child(even) {
        background-color: #f9fafb;
    }

    .empty {
        padding: 48px;
        text-align: center;
        color: #64748b;
        border: 2px dashed #e2e8f0;
        border-radius: 16px;
        background: #f8fafc;
        width: 80%;
        margin: 40px auto;
    }
</style>

<div class="container">
    <?php include 'components/admin/admin_navbar.php'; ?>

    <main class="main-content">
        <h2 style="margin:24px;">Distinct Customers</h2>

        <?php
        $sql = "SELECT name, phone, address 
                FROM customer 
                GROUP BY phone 
                ORDER BY name ASC";

        $result = $connection->query($sql);

        if ($result->num_rows === 0) {
            echo '<div class="empty">No customers found.</div>';
        } else {
            echo '<table class="customer-table">';
            echo '<tr><th>#</th><th>Name</th><th>Phone</th><th>Address</th></tr>';

            $count = 1;
            while ($c = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $count++ . '</td>';
                echo '<td>' . htmlspecialchars($c['name']) . '</td>';
                echo '<td>' . htmlspecialchars($c['phone']) . '</td>';
                echo '<td>' . htmlspecialchars($c['address']) . '</td>';
                echo '</tr>';
            }

            echo '</table>';
        }
        ?>
    </main>
</div>

<?php include 'components/admin/admin_footer.php'; ?>
