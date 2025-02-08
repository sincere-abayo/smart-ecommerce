// Sample user data (replace with actual API calls)
const users = [
    { id: 1, name: 'John Doe', email: 'john@example.com', role: 'admin', status: 'active' },
    { id: 2, name: 'Jane Smith', email: 'jane@example.com', role: 'employee', status: 'active' },
    { id: 3, name: 'Bob Wilson', email: 'bob@example.com', role: 'customer', status: 'inactive' }
];

// DOM Elements
const userTableBody = document.getElementById('userTableBody');
const roleFilter = document.getElementById('roleFilter');
const addUserBtn = document.getElementById('addUserBtn');
const userModal = document.getElementById('userModal');
const userForm = document.getElementById('userForm');
const cancelBtn = document.getElementById('cancelBtn');

// Render users table
function renderUsers(filteredUsers = users) {
    userTableBody.innerHTML = '';
    filteredUsers.forEach(user => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td class="px-6 py-4 whitespace-nowrap">${user.id}</td>
            <td class="px-6 py-4 whitespace-nowrap">${user.name}</td>
            <td class="px-6 py-4 whitespace-nowrap">${user.email}</td>
            <td class="px-6 py-4 whitespace-nowrap">${user.role}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                    ${user.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                    ${user.status}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <button class="text-blue-500 hover:text-blue-700 mr-2" onclick="editUser(${user.id})">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="text-red-500 hover:text-red-700" onclick="deleteUser(${user.id})">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;
        userTableBody.appendChild(row);
    });
}

// Filter users by role
roleFilter.addEventListener('change', (e) => {
    const selectedRole = e.target.value;
    const filteredUsers = selectedRole === 'all' 
        ? users 
        : users.filter(user => user.role === selectedRole);
    renderUsers(filteredUsers);
});

// Show/Hide modal
addUserBtn.addEventListener('click', () => {
    userModal.classList.remove('hidden');
    userForm.reset();
});

cancelBtn.addEventListener('click', () => {
    userModal.classList.add('hidden');
});

// Handle form submission
userForm.addEventListener('submit', (e) => {
    e.preventDefault();
    const formData = new FormData(userForm);
    const userData = {
        id: users.length + 1,
        name: formData.get('name'),
        email: formData.get('email'),
        role: formData.get('role'),
        status: 'active'
    };
    users.push(userData);
    renderUsers();
    userModal.classList.add('hidden');
});

// Initial render
renderUsers();
