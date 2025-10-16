// ===== DATA =====
const LISTINGS = [
  { id: 1, title: "Old Town Studio - Sarajevo", municipality: "Stari Grad", priceBAM: 50, rating: 4.8, beds: 1, baths: 1, size: 28, cover: "assets/listings/1a.jpg", gallery: ["assets/listings/1a.jpg","assets/listings/1a.jpg"], heating: "Central", amenities: ["Wi-Fi", "Heating", "Kitchen"], description: "Cozy studio in Baščaršija. Walk everywhere." },
  { id: 2, title: "Riverside Loft - Mostar", municipality: "Mostar", priceBAM: 70, rating: 4.6, beds: 1, baths: 1, size: 36, cover: "assets/listings/cottage.jpg", gallery: ["assets/listings/cottage.jpg","assets/listings/cottage.jpg"], heating: "Electric", amenities: ["Wi-Fi", "AC", "Kitchen"], description: "Modern loft near the Neretva with lovely views." },
  { id: 3, title: "Blagaj resort", municipality: "Blagaj", priceBAM: 160, rating: 4.9, beds: 3, baths: 2, size: 80, cover: "assets/listings/exoticpool.jpg", gallery: ["assets/listings/exoticpool.jpg","assets/listings/exoticpool.jpg"], heating: "Wood stove", amenities: ["Fireplace", "Parking", "Kitchen", "Pool"], description: "Warm wooden cabin close to ski slopes." },
  { id: 4, title: "Sunny Two-Bedroom - Banja Luka", municipality: "Banja Luka", priceBAM: 95, rating: 4.5, beds: 2, baths: 1, size: 62, cover: "assets/listings/cozyroom.jpg", gallery: ["assets/listings/cozyroom.jpg","assets/listings/cozyroom.jpg"], heating: "Central", amenities: ["Wi-Fi", "Balcony", "Lift"], description: "Bright apartment near the center." },
  { id: 5, title: "City Center Apartment - Tuzla", municipality: "Tuzla", priceBAM: 60, rating: 4.4, beds: 1, baths: 1, size: 35, cover: "assets/listings/oneroom.jpg", gallery: ["assets/listings/oneroom.jpg","assets/listings/oneroom.jpg"], heating: "Electric", amenities: ["Wi-Fi", "Washer", "Kitchen"], description: "Close to cafes and Salt Lakes." },
  { id: 6, title: "Old Bridge View - Mostar", municipality: "Mostar", priceBAM: 110, rating: 4.7, beds: 2, baths: 1, size: 55, cover: "assets/listings/bohoroom.jpg", gallery: ["assets/listings/bohoroom.jpg","assets/listings/bohoroom.jpg"], heating: "Central", amenities: ["Wi-Fi", "AC", "Kitchen"], description: "Steps from Stari Most with a balcony." },
];

// ===== HELPERS / RENDERERS =====
function cardHTML(item) {
  return `
  <div class="col">
    <div class="card h-100 shadow-sm position-relative">
      ${item.rating >= 4.8 ? `<div class="badge bg-success text-white position-absolute" style="top:0.5rem;right:0.5rem">Top Rated</div>` : ""}
      <img class="card-img-top" src="${item.cover}" alt="${item.title}">
      <div class="card-body d-flex flex-column justify-content-between">
        <h3 class="h6 mb-1">${item.title}</h3>
        <div class="text-muted small mb-2">${item.municipality}</div>
        <div class="d-flex justify-content-between align-items-center">
          <div class="text-muted small"><i class="bi bi-star-fill text-warning"></i> ${item.rating}</div>
          <div><span class="fw-semibold">${item.priceBAM}</span> <span class="text-muted">BAM / night</span></div>
        </div>
      </div>
      <div class="card-footer bg-transparent border-0 pt-0">
      <a class="btn btn-outline-dark w-100" href="#listing" data-id="${item.id}">View</a>
      </div>
    </div>
  </div>`;
}
// capture clicks on any "View" button
$(document).on("click", 'a[href="#listing"]', function () {
  const id = $(this).data("id");
  window.lastClickedListingId = id;
});

// also handle "View Listing" buttons inside dashboards
$(document).on("click", ".view-listing-btn", function () {
  const id = $(this).data("id");
  if (id) {
    window.lastClickedListingId = id;
    window.location.hash = "#listing";
  }
});

function renderListingsPage() {
  const grid = document.getElementById("listingsGrid");
  if (!grid) return;
  grid.innerHTML = LISTINGS.map(cardHTML).join("");
}

function renderHomeFeatured() {
  const grid = document.getElementById("featuredGrid");
  if (!grid) return;
  grid.innerHTML = LISTINGS.slice(0, 4).map(cardHTML).join("");
}

function renderListingDetailsPage(id) {
  const item = LISTINGS.find(x => String(x.id) === String(id));
  const title = document.getElementById("ldTitle");
  if (!item || !title) return;

  document.getElementById("ldTitle").textContent = item.title;
  document.getElementById("ldPrice").textContent = `${item.priceBAM} BAM / night`;
  document.getElementById("ldRating").innerHTML = `<i class="bi bi-star-fill text-warning"></i> ${item.rating}`;
  document.getElementById("ldMunicipality").textContent = item.municipality;
  document.getElementById("ldDescription").textContent = item.description;
  document.getElementById("ldHeating").textContent = item.heating;
  document.getElementById("ldAmenities").innerHTML = item.amenities.map(a => `<li>• ${a}</li>`).join("");
  document.getElementById("ldHero").src = item.cover;
  document.getElementById("ldHero").alt = item.title;
  document.getElementById("ldGallery").innerHTML = item.gallery.map(src => `
    <div class="col-6">
      <img class="img-fluid rounded" src="${src}" alt="">
    </div>`).join("");
}

function initRegisterPage() {
  const form = document.getElementById("registerForm");
  if (!form) return;

  form.addEventListener("submit", e => {
    e.preventDefault();
    const role = document.getElementById("roleSelect").value;
    if (!role) { alert("Please select your role before registering."); return; }
    const name = document.getElementById("firstName").value || "User";
    localStorage.setItem("userRole", role);
    localStorage.setItem("userName", name);
    if (role === "landlord") window.location.hash = "#dashboard_landlord";
    else window.location.hash = "#dashboard_user";
  });
}

function initContactPage() {
  const form = document.getElementById("contactForm");
  const toastEl = document.getElementById("contactToast");
  if (!form || !toastEl) return;
  form.addEventListener("submit", e => {
    e.preventDefault();
    if (window.bootstrap?.Toast) {
      const toast = new bootstrap.Toast(toastEl);
      toast.show();
    }
    form.reset();
  });
}

// ===== SPAPP ROUTES =====
$(document).ready(function () {
  const app = $.spapp({
    defaultView: "home",
    templateDir: "views/"
  });

  // HOME
  app.route({ view: "home", onCreate: renderHomeFeatured });

  // LISTINGS
  app.route({ view: "listings", onCreate: renderListingsPage });

  // LISTING (details)
  app.route({
  view: "listing",
  onCreate: function () {
    // get id either from hash or from data attribute
    let id = null;

    // try to get id from hash (when loaded directly)
    const params = new URLSearchParams(window.location.hash.split("?")[1]);
    if (params.has("id")) {
      id = params.get("id");
    }

    // or if user clicked the card link
    if (!id && window.lastClickedListingId) {
      id = window.lastClickedListingId;
    }

    // render the listing
    renderListingDetailsPage(id);
  }
});


  // REGISTER
  app.route({ view: "register", onCreate: initRegisterPage });

  // CONTACT
  app.route({ view: "contact", onCreate: initContactPage });

  // DASHBOARD (role-guarded) — Landlord
  app.route({
    view: "dashboard_landlord",
    onCreate: function () {
      const role = localStorage.getItem("userRole");
      if (role !== "landlord") { window.location.hash = "#login"; return; }
      setTimeout(() => {
        const el = document.getElementById("calendar");
        if (el && window.FullCalendar) {
          const calendar = new FullCalendar.Calendar(el, {
            initialView: "dayGridMonth",
            height: 550,
            headerToolbar: { left: "prev,next today", center: "title", right: "" },
            events: [
              { title: "Sara K. - Old Town Studio", start: "2025-10-02", end: "2025-10-04", color: "#dc3545" },
              { title: "John D. - Blagaj Resort", start: "2025-10-10", end: "2025-10-13", color: "#dc3545" },
              { title: "Marko T. - Sunny Apartment", start: "2025-10-20", end: "2025-10-22", color: "#dc3545" }
            ]
          });
          calendar.render();
        }
      }, 150);
    }
  });

  // DASHBOARD (role-guarded) — User
  app.route({
    view: "dashboard_user",
    onCreate: function () {
      const role = localStorage.getItem("userRole");
      if (role !== "user") { window.location.hash = "#login"; return; }
      setTimeout(() => {
        const el = document.getElementById("calendar");
        if (el && window.FullCalendar) {
          const calendar = new FullCalendar.Calendar(el, {
            initialView: "dayGridMonth",
            height: 500,
            headerToolbar: { left: "prev,next today", center: "title", right: "" },
            events: [
              { title: "Blagaj Resort", start: "2025-10-10", end: "2025-10-13", color: "#198754" },
              { title: "Old Town Studio", start: "2025-10-02", end: "2025-10-04", color: "#198754" }
            ]
          });
          calendar.render();
        }
      }, 150);
    }
  });

  app.run();
});
