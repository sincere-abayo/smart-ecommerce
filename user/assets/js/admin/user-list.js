// Sample user data
const users = [
    {
        id: 1,
        name: 'John Doe',
        email: 'john@example.com',
        role: 'admin',
        status: 'active',
        lastLogin: '2023-10-20 14:30',
        avatar: 'https://via.placeholder.com/40'
    },
    // Add more sample users here
];

// Render user table
function renderUsers(userList = users) {
    const tbody = document.getElementById('userTableBody');
    tbody.innerHTML = userList.map(user => `
        <tr>
            <td class="px-6 py-4">
                <input type="checkbox" class="user-select" value="${user.id}">
            </td>
            <td class="px-6 py-4">
                <div class="flex items-center">
                    <img class="h-10 w-10 rounded-full" src="${user.avatar}" alt="">
                    <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900">${user.name}</div>
                        <div class="text-sm text-gray-500">${user.email}</div>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                    ${user.role}
                </span>
            </td>
            <td class="px-6 py-4">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                    ${getStatusColor(user.status)}">
                    ${user.status}
                </span>
            </td>
            <td class="px-6 py-4 text-sm text-gray-500">
                ${user.lastLogin}
            </td>
            <td class="px-6 py-4">
                <button onclick="editUser(${user.id})" class="text-blue-600 hover:text-blue-900 mr-3">
                    <i class="fas fa-edit"></i>
                </button>
                <button onclick="deleteUser(${user.id})" class="text-red-600 hover:text-red-900">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    `).join('');
}

// Helper function for status colors
function getStatusColor(status) {
    const colors = {
        active: 'bg-green-100 text-green-800',
        inactive: 'bg-gray-100 text-gray-800',
        suspended: 'bg-red-100 text-red-800'
    };
    return colors[status] || colors.inactive;
}

// Filter functions
function applyFilters() {
    const roleFilter = document.getElementById('roleFilter').value;
    const statusFilter = document.getElementById('statusFilter').value;
    
    let filteredUsers = users;
    if (roleFilter) {
        filteredUsers = filteredUsers.filter(user => user.role === roleFilter);
    }
    if (statusFilter) {
        filteredUsers = filteredUsers.filter(user => user.status === statusFilter);
    }
    
    renderUsers(filteredUsers);
}

// Export users to CSV
function exportUsers() {
    const csvContent = users.map(user => 
        Object.values(user).join(',')
    ).join('\n');
    
    const blob = new Blob([csvContent], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'users.csv';
    a.click();
}

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    renderUsers();
});
