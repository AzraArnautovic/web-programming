/*// ===== DATA =====
const LISTINGS = [
  { id: 1, title: "Old Town Studio - Sarajevo", municipality: "Stari Grad", priceBAM: 50, beds: 1, baths: 1, size: 28, cover: "assets/listings/1a.jpg", gallery: ["assets/listings/1a.jpg","assets/listings/1a.jpg"], heating: "Central", amenities: ["Wi-Fi", "Heating", "Kitchen"], description: "Cozy studio in Baščaršija. Walk everywhere." },
  { id: 2, title: "Riverside Loft - Mostar", municipality: "Mostar", priceBAM: 70, beds: 1, baths: 1, size: 36, cover: "assets/listings/cottage.jpg", gallery: ["assets/listings/cottage.jpg","assets/listings/cottage.jpg"], heating: "Electric", amenities: ["Wi-Fi", "AC", "Kitchen"], description: "Modern loft near the Neretva with lovely views." },
  { id: 3, title: "Blagaj resort", municipality: "Blagaj", priceBAM: 160, beds: 3, baths: 2, size: 80, cover: "assets/listings/exoticpool.jpg", gallery: ["assets/listings/exoticpool.jpg","assets/listings/exoticpool.jpg"], heating: "Wood stove", amenities: ["Fireplace", "Parking", "Kitchen", "Pool"], description: "Warm wooden cabin close to ski slopes." },
  { id: 4, title: "Sunny Two-Bedroom - Banja Luka", municipality: "Banja Luka", priceBAM: 95, beds: 2, baths: 1, size: 62, cover: "assets/listings/cozyroom.jpg", gallery: ["assets/listings/cozyroom.jpg","assets/listings/cozyroom.jpg"], heating: "Central", amenities: ["Wi-Fi", "Balcony", "Lift"], description: "Bright apartment near the center." },
  { id: 5, title: "City Center Apartment - Tuzla", municipality: "Tuzla", priceBAM: 60,  beds: 1, baths: 1, size: 35, cover: "assets/listings/oneroom.jpg", gallery: ["assets/listings/oneroom.jpg","assets/listings/oneroom.jpg"], heating: "Electric", amenities: ["Wi-Fi", "Washer", "Kitchen"], description: "Close to cafes and Salt Lakes." },
  { id: 6, title: "Old Bridge View - Mostar", municipality: "Mostar", priceBAM: 110,  beds: 2, baths: 1, size: 55, cover: "assets/listings/bohoroom.jpg", gallery: ["assets/listings/bohoroom.jpg","assets/listings/bohoroom.jpg"], heating: "Central", amenities: ["Wi-Fi", "AC", "Kitchen"], description: "Steps from Stari Most with a balcony." },
];

// ===== HELPERS / RENDERERS =====
function cardHTML(item, isFeatured = false) {
  return `
  <div class="col">
    <div class="card h-100 shadow-sm position-relative">
    ${isFeatured ? `<div class="badge bg-success text-white position-absolute" style="top:0.5rem; right:0.5rem;">Top Pick</div>` : ""}
      <img class="card-img-top" src="${item.cover}" alt="${item.title}">
      <div class="card-body d-flex flex-column justify-content-between">
        <h3 class="h6 mb-1">${item.title}</h3>
        <div class="text-muted small mb-2">${item.municipality}</div>
        <div class="d-flex justify-content-between align-items-center">
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
  grid.innerHTML = LISTINGS.map(item => cardHTML(item)).join("");
}
function renderHomeFeatured() {
  const grid = document.getElementById("featuredGrid");
  if (!grid) return;
  grid.innerHTML = LISTINGS.slice(0, 4).map(item => cardHTML(item, true)).join("");
}

function renderListingDetailsPage(id) {
  const item = LISTINGS.find(x => String(x.id) === String(id));
  const title = document.getElementById("ldTitle");
  if (!item || !title) return;

  document.getElementById("ldTitle").textContent = item.title;
  document.getElementById("ldPrice").textContent = `${item.priceBAM} BAM / night`;
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
    const name = form.querySelector("#name")?.value || "Anonymous";
    const message = form.querySelector("#message")?.value || "No message.";
    const date = new Date().toLocaleString();

    const senderRole = localStorage.getItem("userRole");
    const receiverKey = senderRole === "landlord" ? "userInbox" : "landlordInbox";

    const inbox = JSON.parse(localStorage.getItem(receiverKey) || "[]");
    inbox.push({ sender: name, text: message, date });
    localStorage.setItem(receiverKey, JSON.stringify(inbox));

    if (window.bootstrap?.Toast) {
      const toast = new bootstrap.Toast(toastEl);
      toast.show();
    }
    form.reset();
  });
}*/
toastr.options = {
  positionClass: "toast-top-right",
  timeOut: 3000,
  extendedTimeOut: 1000,
  closeButton: true,
  progressBar: true,
  preventDuplicates: true,
  newestOnTop: true
};

// ===== SPAPP ROUTES =====
$(document).ready(function () {
  const app = $.spapp({
    defaultView: "home",
    templateDir: "views/",
    pageNotFoundView: "404"
  });

  // Home view
  app.route({
    view: "home",
    load: "home.html",
    onReady: function () {
      UserService.loadFeaturedListings();
    }
  });

  // Listings view
  app.route({
    view: "listings",
    load: "listings.html",
    onReady: function () {
      UserService.loadAllListings();
    }
  });

  // Single listing view
 app.route({
  view: "listing",
  load: "listing.html",
  onReady: function () {
    const id = sessionStorage.getItem("selectedListingId");
    const user = JSON.parse(localStorage.getItem("user"));
    if (id) {
      UserService.loadListingById(id);
 // Show booking button only if role is USER
      if (user?.role === Constants.USER_ROLE) {
        $("#bookListingBtn").removeClass("d-none");
      } else {
        $("#bookListingBtn").addClass("d-none");
      }
      // Wire up booking button
      $(document).off("click", "#bookListingBtn").on("click", "#bookListingBtn", function () {
        $("#reservationListing").val(id);
        $("#newReservationModal").modal("show");
      });
      // Wire up confirm button inside modal
      $(document).off("click", "#confirmReservationBtn").on("click", "#confirmReservationBtn", function () {
        UserService.createReservationFromListing(id);
      });
    }
  }
});

app.route({
  view: "dashboard_landlord",
  load: "dashboard_landlord.html",
  onReady: function () {
    const token = localStorage.getItem("user_token");
    const user = token ? Utils.parseJwt(token)?.user : null;

    if (!user || user.role !== "landlord") {
      window.location.replace("index.html#login");
      return;
    }
    UserService.loadLandlordDashboard();
    // Open Add New
    $(document).off("click", "#openAddListing").on("click", "#openAddListing", function () {
      $("#listingForm")[0].reset();
      $("#listingForm").removeData("listingId");
      $("#addListingModal").modal("show");
    });

    // Save on submit
    $(document).off("submit", "#listingForm").on("submit", "#listingForm", function (e) {
      e.preventDefault();
      UserService.saveListing();
    });

    // Edit
    $(document).off("click", ".btn-edit-listing").on("click", ".btn-edit-listing", function () {
      const id = $(this).data("id");
      UserService.openEditListing(id);
    });

    // Delete
    $(document).off("click", ".btn-delete-listing").on("click", ".btn-delete-listing", function () {
      const id = $(this).data("id");
      if (confirm("Delete this listing?")) {
        UserService.deleteListing(id);
      }
    });
    $(document).off("submit", "#messageForm").on("submit", "#messageForm", function (e) {
  e.preventDefault();
  UserService.sendMessage();
});
  }
});

app.route({
  view: "dashboard_user",
  load: "dashboard_user.html",
  onReady: function () {
    const token = localStorage.getItem("user_token");
    const user = token ? Utils.parseJwt(token)?.user : null;

    if (!user || user.role !== Constants.USER_ROLE) {
      window.location.replace("index.html#login");
      return;
    }

    UserService.loadUserDashboard();

    // New reservation
$(document).off("click", "#confirmReservationBtn").on("click", "#confirmReservationBtn", function () {
  UserService.createReservation();
});

// Edit reservation
$(document).off("click", ".btn-edit-reservation").on("click", ".btn-edit-reservation", function () {
  const id = $(this).data("id");
  UserService.openEditReservation(id);
});
$(document).off("click", "#saveReservationChangesBtn").on("click", "#saveReservationChangesBtn", function () {
  UserService.saveReservationChanges();
});

$(document).off("click", ".btn-cancel-reservation").on("click", ".btn-cancel-reservation", function () {
  const id = $(this).data("id");
  $("#confirmCancelReservationBtn").data("reservationId", id);
  $("#cancelBookingModal").modal("show");
});

$(document).off("click", "#confirmCancelReservationBtn").on("click", "#confirmCancelReservationBtn", function () {
  const id = $(this).data("reservationId");
  UserService.cancelReservation(id);
});

// Wishlist
$(document).off("click", "#confirmAddWishlistBtn").on("click", "#confirmAddWishlistBtn", function () {
  UserService.addToWishlist();
});
// Messages
$(document).off("submit", "#messageForm").on("submit", "#messageForm", function (e) {
  e.preventDefault();
  UserService.sendMessage();
});
 // Populate listing selects for modals
    UserService.populateListingSelects();
  }
});
// View listing from wishlist
$(document).off("click", ".view-listing-btn").on("click", ".view-listing-btn", function () {
  const id = $(this).data("id");
  if (id) {
    sessionStorage.setItem("selectedListingId", id);
    window.location.replace("index.html#listing");
  }
});


  app.run();
});

 /* // HOME
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
    renderInbox(); 
    const role = localStorage.getItem("userRole");
    if (role !== "landlord") { 
      window.location.hash = "#login"; 
      return; 
    }

    const el = document.getElementById("calendar");
    if (el && window.FullCalendar) {
      const calendar = new FullCalendar.Calendar(el, {
        initialView: "dayGridMonth",
        height: 550,
        headerToolbar: { left: "prev,next today", center: "title", right: "" },

        // Dynamic events from backend
        events: function(fetchInfo, successCallback, failureCallback) {
          RestClient.get(`reservations/landlord/${landlordId}`, function(response) {
            // Transform backend data into FullCalendar format
            const events = response.data.map(r => ({
              title: `${r.user_name} - ${r.listing_name}`,
              start: r.start_date,
              end: r.end_date,
              color: "#dc3545"
            }));
            successCallback(events);
          }, function(error) {
            console.error("Failed to load reservations:", error);
            failureCallback(error);
          });
        }
      });
      calendar.render();
    }
  }
});


 // DASHBOARD (role-guarded) — User
app.route({
  view: "dashboard_user",
  onCreate: function () {
    renderInbox(); 
    const role = localStorage.getItem("userRole");
    if (role !== "user") { 
      window.location.hash = "#login"; 
      return; 
    }

    const el = document.getElementById("calendar");
    if (el && window.FullCalendar) {
      const calendar = new FullCalendar.Calendar(el, {
        initialView: "dayGridMonth",
        height: 500,
        headerToolbar: { left: "prev,next today", center: "title", right: "" },

        // Dynamic events for the logged-in user
        events: function(fetchInfo, successCallback, failureCallback) {
          RestClient.get(`reservations/user/${userId}`, function(response) {
            // Transform backend data into FullCalendar format
            const events = response.data.map(r => ({
              title: r.listing_name,   // show just the property name for user
              start: r.start_date,
              end: r.end_date,
              color: "#198754"
            }));
            successCallback(events);
          }, function(error) {
            console.error("Failed to load user reservations:", error);
            failureCallback(error);
          });
        }
      });
      calendar.render();
    }
  }
});


        // year + dynamic dashboard link
        document.getElementById('year').textContent = new Date().getFullYear();
        const role = localStorage.getItem("userRole");
        const navDash = document.getElementById("navDashboard");
        if (navDash) {
          if (role === "user") navDash.href = "#dashboard_user";
          else if (role === "landlord") navDash.href = "#dashboard_landlord";
          else navDash.href = "#login";
        }
 function renderInbox() {
  const role = localStorage.getItem("userRole");
  const inboxKey = role === "landlord" ? "landlordInbox" : "userInbox";
  const messages = JSON.parse(localStorage.getItem(inboxKey) || "[]");

  const container = document.getElementById("inboxContainer");
  if (!container) return;

  if (messages.length === 0) {
    container.innerHTML = `<div class="text-muted small">No messages yet.</div>`;
    return;
  }

  container.innerHTML = messages.map(msg => `
    <div class="alert alert-light border mb-2">
      <i class="bi bi-person-circle text-success me-2"></i>
      <strong>${msg.sender}</strong> 
      <span class="text-muted small">(${msg.date})</span>
      <div>${msg.text}</div>
    </div>
  `).join("");
}
 });
*/