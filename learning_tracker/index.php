<?php
require_once 'config.php';
require_once 'functions.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        addTopic($pdo, $_POST['topik'], $_POST['status']);
    } elseif (isset($_POST['update'])) {
        updateTopic($pdo, $_POST['id'], $_POST['topik'], $_POST['status']);
    } elseif (isset($_POST['delete'])) {
        deleteTopic($pdo, $_POST['id']);
    }
}

// Get all topics
$topics = getAllTopics($pdo);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learning Tracker</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-10">
                <h1 class="text-4xl font-bold text-indigo-700 mb-2">📚 Learning Tracker</h1>
                <p class="text-gray-600">Pantau progress belajarmu dengan mudah</p>
            </div>

            <!-- Add Topic Form -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">➕ Tambah Topik Belajar Baru</h2>
                <form method="POST" class="space-y-4">
                    <div>
                        <label for="topik" class="block text-sm font-medium text-gray-700 mb-1">Topik Belajar</label>
                        <input type="text" id="topik" name="topik" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            placeholder="Contoh: Belajar PHP Dasar">
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select id="status" name="status" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="Belum Mulai">Belum Mulai</option>
                            <option value="Sedang Belajar">Sedang Belajar</option>
                            <option value="Selesai">Selesai</option>
                        </select>
                    </div>
                    <button type="submit" name="add"
                        class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 transition duration-200">
                        Tambahkan
                    </button>
                </form>
            </div>

            <!-- Topics List -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">📋 Daftar Topik Belajar</h2>
                
                <!-- Filter Buttons -->
                <div class="flex space-x-2 mb-4">
                    <a href="?filter=all" class="px-3 py-1 bg-gray-200 text-gray-800 rounded-full text-sm hover:bg-gray-300">Semua</a>
                    <a href="?filter=Belum Mulai" class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm hover:bg-red-200">Belum Mulai</a>
                    <a href="?filter=Sedang Belajar" class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm hover:bg-yellow-200">Sedang Belajar</a>
                    <a href="?filter=Selesai" class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm hover:bg-green-200">Selesai</a>
                </div>

                <?php if (empty($topics)): ?>
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-book-open text-4xl mb-2"></i>
                        <p>Belum ada topik belajar. Yuk tambahkan yang pertama!</p>
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Topik</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($topics as $topic): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($topic['topik']) ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php 
                                                $statusClass = [
                                                    'Belum Mulai' => 'bg-red-100 text-red-800',
                                                    'Sedang Belajar' => 'bg-yellow-100 text-yellow-800',
                                                    'Selesai' => 'bg-green-100 text-green-800'
                                                ];
                                            ?>
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full <?= $statusClass[$topic['status']] ?>">
                                                <?= $topic['status'] ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <!-- Edit Button -->
                                            <button onclick="openEditModal(
                                                '<?= $topic['id'] ?>', 
                                                '<?= htmlspecialchars($topic['topik'], ENT_QUOTES) ?>', 
                                                '<?= $topic['status'] ?>'
                                            )" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <!-- Delete Button -->
                                            <form method="POST" class="inline">
                                                <input type="hidden" name="id" value="<?= $topic['id'] ?>">
                                                <button type="submit" name="delete" class="text-red-600 hover:text-red-900" onclick="return confirm('Yakin ingin menghapus?')">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">✏️ Edit Topik</h3>
                <button onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form method="POST" id="editForm">
                <input type="hidden" name="id" id="editId">
                <div class="space-y-4">
                    <div>
                        <label for="editTopik" class="block text-sm font-medium text-gray-700 mb-1">Topik Belajar</label>
                        <input type="text" id="editTopik" name="topik" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="editStatus" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select id="editStatus" name="status" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="Belum Mulai">Belum Mulai.</option>
                            <option value="Sedang Belajar">Sedang Belajar.</option>
                            <option value="Selesai">Selesai.</option>
                        </select>
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="closeEditModal()"
                        class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit" name="update"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(id, topik, status) {
            document.getElementById('editId').value = id;
            document.getElementById('editTopik').value = topik;
            document.getElementById('editStatus').value = status;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
    </script>
</body>
</html>