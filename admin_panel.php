<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - <?= htmlspecialchars($site_name) ?></title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;700&display=swap');
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        .card {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container-fluid">
        <h1 class="mb-4">Admin Panel - <?= htmlspecialchars($site_name) ?></h1>

        <!-- Site Settings -->
        <div class="card">
            <div class="card-header">
                <h3>Site Settings</h3>
            </div>
            <div class="card-body">
                <form action="admin_panel.php" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="title">Site Title:</label>
                        <input type="text" id="title" name="title" class="form-control" value="<?= htmlspecialchars($site_name) ?>">
                    </div>
                    <div class="form-group">
                        <label for="footer_legend">Footer Legend:</label>
                        <input type="text" id="footer_legend" name="footer_legend" class="form-control" value="<?= htmlspecialchars($footer_legend) ?>">
                    </div>
                    <div class="form-group">
                        <label for="logo">Upload Logo:</label>
                        <input type="file" id="logo" name="logo" class="form-control">
                        <?php if ($logo_filename): ?>
                            <div class="mt-3">
                                <img src="uploads/<?= htmlspecialchars($logo_filename) ?>" alt="Logo" style="max-width: 200px;">
                                <button type="submit" name="delete_logo" class="btn btn-danger mt-2">Delete Logo</button>
                            </div>
                        <?php endif; ?>
                    </div>
                    <button type="submit" name="update_logo" class="btn btn-primary">Update Settings</button>
                </form>
            </div>
        </div>

        <!-- Advertisement Settings -->
        <div class="card">
            <div class="card-header">
                <h3>Advertisement Settings</h3>
            </div>
            <div class="card-body">
                <form action="admin_panel.php" method="post">
                    <?php
                    $ad_types = ['banner_top' => 'Banner Top', 'banner_bottom' => 'Banner Bottom'];
                    foreach ($ad_types as $type => $label): ?>
                        <div class="form-group">
                            <label for="ad_code_<?= $type ?>"><?= $label ?> Ad Code:</label>
                            <textarea id="ad_code_<?= $type ?>" name="ad_code_<?= $type ?>" class="form-control" rows="4"><?= htmlspecialchars($ads[$type] ?? '') ?></textarea>
                        </div>
                    <?php endforeach; ?>
                    <button type="submit" name="update_ads" class="btn btn-primary">Update Ads</button>
                </form>
            </div>
        </div>

        <!-- User Management -->
        <div class="card">
            <div class="card-header">
                <h3>Manage Users</h3>
            </div>
            <div class="card-body">
                <a href="manage_users.php" class="btn btn-primary">Manage Users</a>
                <a href="add_user.php" class="btn btn-secondary">Add User</a>
            </div>
        </div>

        <!-- Paste Management -->
        <div class="card">
            <div class="card-header">
                <h3>Manage Pastes</h3>
            </div>
            <div class="card-body">
                <a href="manage_pastes.php" class="btn btn-primary">Manage Pastes</a>
            </div>
        </div>
    </div>
    <?php include 'footbar.php'; ?>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
