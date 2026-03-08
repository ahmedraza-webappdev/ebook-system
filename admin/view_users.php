<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management | Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 font-sans">

    <div class="container mx-auto px-4 py-10">
        
        <div class="bg-white rounded-t-2xl shadow-sm p-6 border-b border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-4">
                <a href="index.php" class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-100 text-slate-600 hover:bg-indigo-600 hover:text-white transition-all shadow-sm" title="Back to Admin">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Community Directory</h1>
                    <p class="text-sm text-gray-500">Manage all <?php include("../config/db.php"); echo mysqli_num_rows(mysqli_query($conn, "SELECT id FROM users")); ?> registered members</p>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="index.php" class="hidden md:block text-sm font-semibold text-gray-500 hover:text-indigo-600 transition-colors mr-2">
                    Admin Dashboard
                </a>
                <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl text-sm font-semibold transition-all shadow-lg shadow-indigo-100 flex items-center gap-2 transform active:scale-95">
                    <i class="fa-solid fa-user-plus"></i> Add New User
                </button>
            </div>
        </div>

        <div class="bg-white rounded-b-2xl shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 text-slate-500 uppercase text-[10px] font-black tracking-widest">
                            <th class="px-6 py-5 text-center">ID</th>
                            <th class="px-6 py-5">User Details</th>
                            <th class="px-6 py-5">Contact & Auth</th>
                            <th class="px-6 py-5">Address</th>
                            <th class="px-6 py-5 text-center">Joined Date</th>
                            <th class="px-6 py-5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php
                        $result = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");

                        if(mysqli_num_rows($result) > 0) {
                            while($row = mysqli_fetch_assoc($result)){
                        ?>
                        <tr class="hover:bg-indigo-50/20 transition-colors group">
                            <td class="px-6 py-5 text-center text-xs font-bold text-slate-400">
                                #<?php echo $row['id']; ?>
                            </td>

                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-xs font-bold shadow-md shadow-indigo-100">
                                        <?php echo strtoupper(substr($row['name'], 0, 1)); ?>
                                    </div>
                                    <div>
                                        <span class="font-bold text-slate-800 block text-sm"><?php echo $row['name']; ?></span>
                                        <span class="bg-emerald-100 text-emerald-600 text-[9px] px-2 py-0.5 rounded font-black uppercase tracking-tighter">Active</span>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-5">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2 text-xs text-slate-600">
                                        <i class="fa-regular fa-envelope text-indigo-400 w-4"></i>
                                        <?php echo $row['email']; ?>
                                    </div>
                                    <div class="flex items-center gap-2 text-xs text-slate-600">
                                        <i class="fa-solid fa-phone text-emerald-400 w-4"></i>
                                        <?php echo $row['phone']; ?>
                                    </div>
                                    <div class="flex items-center gap-2 text-[10px] text-slate-400">
                                        <i class="fa-solid fa-key w-4"></i>
                                        <span class="tracking-widest italic">••••••••</span> 
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-5">
                                <p class="text-xs text-slate-500 max-w-[180px] line-clamp-2">
                                    <i class="fa-solid fa-location-dot text-rose-400 mr-1"></i>
                                    <?php echo $row['address']; ?>
                                </p>
                            </td>

                            <td class="px-6 py-5 text-center">
                                <span class="text-xs font-semibold text-slate-600 block">
                                    <?php echo date('M d, Y', strtotime($row['created_at'])); ?>
                                </span>
                                <span class="text-[9px] text-slate-400 uppercase">
                                    <?php echo date('h:i A', strtotime($row['created_at'])); ?>
                                </span>
                            </td>

                            <td class="px-6 py-5 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="edit_user.php?id=<?php echo $row['id']; ?>" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-500 hover:bg-blue-500 hover:text-white transition-all flex items-center justify-center shadow-sm" title="Edit">
                                        <i class="fa-solid fa-pen text-[10px]"></i>
                                    </a>
                                    <a href="delete_user.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Pakka delete karna hai?')" class="w-8 h-8 rounded-lg bg-rose-50 text-rose-400 hover:bg-rose-500 hover:text-white transition-all flex items-center justify-center shadow-sm" title="Delete">
                                        <i class="fa-solid fa-trash text-[10px]"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php 
                            } 
                        } else {
                            echo "<tr><td colspan='6' class='text-center py-20 text-slate-400 italic'>
                                <i class='fa-solid fa-users-slash text-4xl mb-3 block opacity-20'></i>
                                No community members found.
                            </td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            
            <div class="p-6 bg-slate-50/50 border-t border-slate-100 flex justify-between items-center">
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">
                    Database: <span class="text-indigo-600">E-Book System</span>
                </p>
                <div class="flex gap-4">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest flex items-center gap-1">
                        <span class="w-2 h-2 bg-indigo-500 rounded-full"></span> Users: <?php echo mysqli_num_rows($result); ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

</body>
</html>