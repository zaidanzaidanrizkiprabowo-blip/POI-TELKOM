// Sample data for sales and customers
const salesData = [
  "Nandi",
  "Andi",
  "Yandi",
  "April",
  "Octa",
  "Toteng",
  "Yusdi",
  "Syarif"
];

let customers = [
  // Sample initial customers (can be empty)
  {
    odp: "ODP-001",
    name: "Budi",
    address: "Jl. Merdeka No.1",
    phone: "08123456789",
    sales: "Nandi",
    visit: "2024-06-01",
    notes: "Prospect"
  },
  {
    odp: "ODP-002",
    name: "Siti",
    address: "Jl. Sudirman No.2",
    phone: "08198765432",
    sales: "Andi",
    visit: "2024-06-02",
    notes: "Follow up"
  }
];

// DOM elements
const salesListEl = document.getElementById("salesList");
const selectedSalesNameEl = document.getElementById("selectedSalesName");
const totalCustomersEl = document.getElementById("totalCustomers");
const filterSTOEl = document.getElementById("filterSTO");
const searchInputEl = document.getElementById("searchInput");
const customerTableBody = document.querySelector("#customerTable tbody");
const addCustomerBtn = document.getElementById("addCustomerBtn");
const modal = document.getElementById("modal");
const addCustomerForm = document.getElementById("addCustomerForm");
const cancelBtn = document.getElementById("cancelBtn");
const salesInputEl = document.getElementById("salesInput");

let selectedSales = null;

// Initialize sales list and sales dropdown
function initSales() {
  salesListEl.innerHTML = "";
  salesInputEl.innerHTML = "";

  salesData.forEach((salesName) => {
    // Sidebar sales list item
    const li = document.createElement("li");
    li.textContent = salesName;
    li.classList.add("sales-item");
    li.dataset.sales = salesName;

    // Add dot icon
    const dot = document.createElement("span");
    dot.classList.add("dot");
    li.prepend(dot);

    li.addEventListener("click", () => {
      selectSales(salesName);
    });

    salesListEl.appendChild(li);

    // Sales dropdown option
    const option = document.createElement("option");
    option.value = salesName;
    option.textContent = salesName;
    salesInputEl.appendChild(option);
  });
}

// Select a sales person
function selectSales(salesName) {
  selectedSales = salesName;
  selectedSalesNameEl.textContent = salesName;

  // Highlight selected sales in sidebar
  document.querySelectorAll(".sales-item").forEach((el) => {
    el.classList.toggle("active", el.dataset.sales === salesName);
  });

  renderTable();
}

// Render customer table based on filters
function renderTable() {
  const filterSTO = filterSTOEl.value.toLowerCase();
  const searchTerm = searchInputEl.value.toLowerCase();

  // Filter customers by selected sales, STO filter, and search term
  let filteredCustomers = customers.filter((cust) => {
    const matchesSales = selectedSales ? cust.sales === selectedSales : true;
    const matchesSTO = filterSTO ? cust.odp.toLowerCase().includes(filterSTO) : true;
    const matchesSearch =
      cust.name.toLowerCase().includes(searchTerm) ||
      cust.address.toLowerCase().includes(searchTerm) ||
      cust.phone.toLowerCase().includes(searchTerm) ||
      cust.sales.toLowerCase().includes(searchTerm) ||
      cust.visit.toLowerCase().includes(searchTerm) ||
      cust.notes.toLowerCase().includes(searchTerm);

    return matchesSales && matchesSTO && matchesSearch;
  });

  // Clear table body
  customerTableBody.innerHTML = "";

  // Populate table rows
  filteredCustomers.forEach((cust) => {
    const tr = document.createElement("tr");
    const originalIndex = customers.indexOf(cust);

    tr.innerHTML = `
      <td>${cust.odp}</td>
      <td>${cust.name}</td>
      <td>${cust.address}</td>
      <td>${cust.phone}</td>
      <td>${cust.sales}</td>
      <td>${cust.visit}</td>
      <td>${cust.notes}</td>
      <td>
        <button class="btn-delete" data-index="${originalIndex}">Hapus</button>
      </td>
    `;

    customerTableBody.appendChild(tr);
  });

  // Add event listeners for delete buttons
  document.querySelectorAll('.btn-delete').forEach(button => {
    button.addEventListener('click', (e) => {
      const index = parseInt(e.target.dataset.index);
      if (confirm('Apakah Anda yakin ingin menghapus data pelanggan ini?')) {
        customers.splice(index, 1);
        renderTable();
      }
    });
  });

  // Update total customers count
  totalCustomersEl.textContent = filteredCustomers.length;
}

// Open modal
function openModal() {
  modal.classList.remove("hidden");
}

// Close modal
function closeModal() {
  modal.classList.add("hidden");
  addCustomerForm.reset();
}

// Handle form submission to add new customer
addCustomerForm.addEventListener("submit", (e) => {
  e.preventDefault();

  const newCustomer = {
    odp: addCustomerForm.odp.value.trim(),
    name: addCustomerForm.name.value.trim(),
    address: addCustomerForm.address.value.trim(),
    phone: addCustomerForm.phone.value.trim(),
    sales: addCustomerForm.sales.value,
    visit: addCustomerForm.visit.value.trim(),
    notes: addCustomerForm.notes.value.trim()
  };

  customers.push(newCustomer);

  closeModal();
  renderTable();
});

// Cancel button closes modal
cancelBtn.addEventListener("click", () => {
  closeModal();
});

// Add customer button opens modal
addCustomerBtn.addEventListener("click", () => {
  openModal();
});

// Filter and search event listeners
filterSTOEl.addEventListener("change", renderTable);
searchInputEl.addEventListener("input", renderTable);

// Initialize dashboard
function init() {
  initSales();
  selectSales(salesData[0]);
}

init();
