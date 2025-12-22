var UserService = {
  init: function () {
     console.log("UserService.init() fired");
    // If already logged in, redirect to the right dashboard
    var token = localStorage.getItem("user_token");
    console.log(token);
    if (token && token !== undefined) {
      const user = Utils.parseJwt(token)?.user;
      if (user && user.role) {
        if (user.role === Constants.LANDLORD_ROLE || user.role === "landlord") {
          window.location.replace("index.html#dashboard_landlord");
        } else if (user.role === Constants.USER_ROLE) {
          window.location.replace("index.html#dashboard_user");
        } else {
          window.location.replace("index.html#home");
        }
      }
    }
    //fix:after user is logged in remove login button or make it into logout button 

    // Attach validation + submit handler to login form
    $("#login-form").validate({
      submitHandler: function (form) {
          event.preventDefault(); //stop default GET
        var entity = Object.fromEntries(new FormData(form).entries());
         console.log("Login payload:", entity);
        UserService.login(entity);
      },
    });

    $("#register-form").validate({
  submitHandler: function (form) {
    event.preventDefault();
    const entity = Object.fromEntries(new FormData(form).entries());
    console.log("Register payload:", entity);
    UserService.register(entity);
  }
});

  },

  register: function (entity) {
    $.ajax({
  url: Constants.PROJECT_BASE_URL + "/auth/register",
  type: "POST",
  data: JSON.stringify(entity),
  contentType: "application/json",
  dataType: "json",
  success: function (result) {
  toastr.success("Registration successful! Please log in.");
      window.location.replace("index.html#login");
    },
    error: function (xhr) {
      toastr.error(xhr?.responseText || "Registration failed. Please try again.");
    }
  });
},
// sliced all listings for homepage view
loadFeaturedListings: function () {
  $.ajax({
    url: Constants.PROJECT_BASE_URL + "/listings",
    type: "GET",
    dataType: "json",
    headers: {
      "Authentication": localStorage.getItem("user_token")
    },
    success: function (result) {
      let html = "";

      if (result.message || result.length === 0) {
        html = `<p class="text-muted">No listings available.</p>`;
      } else {
        // Limit to first 4 or 6 listings
        const featured = result.slice(3,7);

        featured.forEach(listing => {
          html += `
            <div class="col mb-5">
            <a href="#listing" data-id="${listing.id}" class="text-decoration-none text-dark">
              <div class="card h-100">
                <!-- Use cover_url from DB -->
                <img class="card-img-top" src="${listing.cover_url || 'assets/listings/1a.jpg'}" alt="${listing.title}">
                
                <div class="card-body p-4">
                  <div class="text-center">
                    <h5 class="fw-bolder">${listing.title}</h5>
                    <p>${listing.description || ''}</p>
                    <span class="text-muted">Price: ${listing.price} BAM</span>
                    <p><small>${listing.municipality}, ${listing.address}</small></p>
                  </div>
                </div>
              </div>
              </a>
            </div>
          `;
        });
      }

      $("#featuredGrid").html(html);
    },
    error: function (xhr) {
      toastr.error(xhr?.responseText || "Failed to load featured listings.");
      $("#featuredGrid").html("<p class='text-danger'>Error loading listings.</p>");
    }
  });
},

loadAllListings: function () {
  $.ajax({
    url: Constants.PROJECT_BASE_URL + "/listings",
    type: "GET",
    dataType: "json",
    headers: {
      "Authentication": localStorage.getItem("user_token")
    },
    success: function (result) {
      let html = "";

      if (result.message || result.length === 0) {
        html = `<p class="text-muted">No listings available.</p>`;
      } else {
        result.forEach(listing => {
          html += `
            <div class="col mb-5">
              <a href="#listing" data-id="${listing.id}" class="text-decoration-none text-dark">
                <div class="card h-100">
                  <img class="card-img-top" src="${listing.cover_url || 'assets/listings/1a.jpg'}" alt="${listing.title}">
                  <div class="card-body p-4">
                    <div class="text-center">
                      <h5 class="fw-bolder">${listing.title}</h5>
                      <p>${listing.description || ''}</p>
                      <span class="text-muted">Price: ${listing.price} BAM</span>
                      <p><small>${listing.municipality}, ${listing.address}</small></p>
                    </div>
                  </div>
                </div>
              </a>
            </div>
          `;
        });
      }

      //  Inject the HTML into the grid
      $("#listingsGrid").html(html);

      //  Attach the click handler AFTER the HTML is injected
      $("#listingsGrid").on("click", "a[href='#listing']", function () {
        const id = $(this).data("id");
        if (id) {
          sessionStorage.setItem("selectedListingId", id);
        }
      });
    },
    error: function (xhr) {
      if (window.toastr) toastr.error(xhr?.responseText || "Failed to load all listings.");
      $("#listingsGrid").html("<p class='text-danger'>Error loading listings.</p>");
    }
  });
},


loadListingById: function (listingId) {
  if (!listingId) {
    $("#ldTitle").text("Listing not found.");
    return;
  }
  $.ajax({
    url: Constants.PROJECT_BASE_URL + "/listings/" + listingId,
    type: "GET",
    dataType: "json",
    headers: {
      "Authentication": localStorage.getItem("user_token")
    },
    success: function (listing) {
      // Hero image
      $("#ldHero").attr("src", listing.cover_url || "assets/listings/hero.jpg");
      $("#ldHero").attr("alt", listing.title);

      // Title, municipality, price
      $("#ldTitle").text(listing.title);
      $("#ldMunicipality").text(listing.municipality + ", " + listing.address);
      $("#ldPrice").text(listing.price + " BAM");

      // Description
      $("#ldDescription").text(listing.description || "");

     // Inside UserService.loadListingById or wherever you render amenities
$("#ldAmenities").empty(); // clear old amenities

const ams = (listing.amenities || "").split(",").filter(Boolean);
ams.forEach(am => {
  $("#ldAmenities").append(`<span class="badge bg-light text-dark border">${am.trim()}</span>`);
});

      // Heating
      $("#ldHeating").text(listing.heating);
      $("#ldBeds").text(listing.beds ?? "-");
$("#ldBaths").text(listing.baths ?? "-");
$("#ldSize").text(listing.size_m2 ?? "-");
$("#ldPosted").text(new Date(listing.created_at).toLocaleDateString());

    },
    error: function (xhr) {
      toastr.error(xhr?.responseText || "Failed to load listing details.");
    }
  });
},

loadLandlordDashboard: function () {
  const user = JSON.parse(localStorage.getItem("user")); //we store JWT-decoded user
  if (!user) return;

  // Set landlord name
  $("#landlordName").text(user.first_name + " " + user.last_name);

  // Load listings
  UserService.loadLandlordListings(user.id);

  // Load reservations summary
  UserService.loadLandlordReservations(user.id);

  // Load inbox
  UserService.loadInbox(user.id);

  // Init calendar
  UserService.loadReservationsCalendar(user.id);
},

loadUserDashboard: function () {
  const user = JSON.parse(localStorage.getItem("user"));
  if (!user) return;

  // Header name
  $("#userName").text(user.first_name + " " + user.last_name);

  // Reservations summary + list
  UserService.loadUserReservations(user.id);

  // Wishlist
  UserService.loadUserWishlist(user.id);

  // Inbox (reuse landlord inbox function)
  UserService.loadInbox(user.id);

  // Calendar
  UserService.loadUserCalendar(user.id);
},

loadLandlordListings: function (userId) {
  $.ajax({
    url: Constants.PROJECT_BASE_URL + "/listings/user/" + userId,
    type: "GET",
    headers: { "Authentication": localStorage.getItem("user_token") },
    success: function (listings) {
      if (!Array.isArray(listings)) {
        $("#landlordListings").html("<p class='text-muted'>You have no listings.</p>");
        $("#totalListings").text("0");
        return;
      }

      $("#totalListings").text(listings.length);
      $("#landlordListings").empty();

      listings.forEach(l => {
        $("#landlordListings").append(`
          <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
        <span>${l.title}</span>
        <div>
          <button class="btn btn-sm btn-outline-secondary me-1 btn-edit-listing" data-id="${l.id}">
            <i class="bi bi-pencil"></i> Edit
          </button>
          <button class="btn btn-sm btn-outline-danger btn-delete-listing" data-id="${l.id}">
            <i class="bi bi-trash"></i> Delete
          </button>
        </div>
      </li>
        `);
      });
    },
    error: function () {
      toastr.error("Failed to load listings.");
    }
  });
},


loadLandlordReservations: function (userId) {
  $.ajax({
    url: Constants.PROJECT_BASE_URL + "/reservations/landlord/" + userId,
    type: "GET",
    headers: { "Authentication": localStorage.getItem("user_token") },
    success: function (reservations) {
      if (!Array.isArray(reservations)) {
        $("#reservationsCount").text("0");
        $("#revenue").text("0");
        return;
      }

      const thisMonth = new Date().getMonth() + 1;
      const monthlyReservations = reservations.filter(r => {
        const resMonth = new Date(r.start_date).getMonth() + 1;
        return resMonth === thisMonth;
      });

      const revenue = monthlyReservations.reduce((sum, r) => {
  const price = parseFloat(r.total_price);
  return sum + (isNaN(price) ? 0 : price);
}, 0);

      $("#reservationsCount").text(monthlyReservations.length);
      $("#revenue").text(revenue.toFixed(2));
    },
    error: function () {
      toastr.error("Failed to load reservations.");
    }
  });
},


loadInbox: function (userId) {
  $.ajax({
    url: Constants.PROJECT_BASE_URL + "/messages/inbox/" + userId,
    type: "GET",
    headers: { "Authentication": localStorage.getItem("user_token") },
    success: function (messages) {
      if (!Array.isArray(messages)) {
        $("#inboxContainer").html("<p class='text-muted'>Inbox is empty.</p>");
        return;
      }

      $("#inboxContainer").empty();
      messages.forEach(m => {
        $("#inboxContainer").append(`
          <div class="border-bottom py-2">
            <strong>From:</strong> ${m.sender_id}<br>
            <span>${m.content}</span>
          </div>
        `);
      });
    },
    error: function () {
      toastr.error("Failed to load inbox.");
    }
  });
},

loadReservationsCalendar: function (userId) {
  const el = document.getElementById("calendar");
  if (!el) return;
  $.ajax({
    url: Constants.PROJECT_BASE_URL + "/reservations/landlord/" + userId,
    type: "GET",
    headers: { "Authentication": localStorage.getItem("user_token") },
    success: function (reservations) {
      if (!Array.isArray(reservations)) return;

      const events = reservations.map(r => ({
        title: r.title,
        start: r.start_date,
        end: r.end_date,
        color: "#28a745" //green for confirmed 
      }));
      const calendar = new FullCalendar.Calendar(el, {
        initialView: "dayGridMonth",
        events: events
      });
      calendar.render();
    }
  });
},

openEditListing: function (id) {
    if (!id) { toastr.error("Invalid listing id."); return; }
    $.ajax({
      url: Constants.PROJECT_BASE_URL + "/listings/" + id,
      type: "GET",
      headers: { "Authentication": localStorage.getItem("user_token") },
      success: function (listing) {
        // Populate modal fields
 $("#listingForm [name='title']").val(listing.title || "");
      $("#listingForm [name='municipality']").val(listing.municipality || "");
      $("#listingForm [name='address']").val(listing.address || "");
      $("#listingForm [name='price']").val(listing.price ?? "");
      $("#listingForm [name='beds']").val(listing.beds ?? 1);
      $("#listingForm [name='baths']").val(listing.baths ?? 1);
      $("#listingForm [name='heating']").val(listing.heating || "Central");
      $("#listingForm [name='size_m2']").val(listing.size_m2 ?? "");
      $("#listingForm [name='cover_url']").val(listing.cover_url || "");
      $("#listingForm [name='amenities']").val(listing.amenities || "");
      $("#listingForm [name='description']").val(listing.description || "");

        // Store ID so we know if it's edit vs add
        $("#listingForm").data("listingId", id);

        // Show modal
        $("#addListingModal").modal("show");
      },
      error: function (xhr) {
              console.error("Open edit error:", xhr.status, xhr.responseText);
        toastr.error("Failed to load listing for edit.");
      }
    });
  },

  // Delete Listing
  deleteListing: function (id) {
      if (!id) { toastr.error("Invalid listing id."); return; }
    $.ajax({
      url: Constants.PROJECT_BASE_URL + "/listings/" + id,
      type: "DELETE",
      headers: { "Authentication": localStorage.getItem("user_token") },
      success: function () {
        toastr.success("Listing deleted.");
        const user = JSON.parse(localStorage.getItem("user"));
        UserService.loadLandlordListings(user.id);
      },
      error: function () {
        toastr.error("Failed to delete listing.");
      }
    });
  },

  // Save Listing (Add or Edit)
    saveListing: function () {
  const raw = Object.fromEntries(new FormData(document.getElementById("listingForm")).entries());

  // Build payload with proper types
  const payload = {
    title: raw.title?.trim(),
    municipality: raw.municipality?.trim(),
    address: raw.address?.trim(),
    price: raw.price ? parseFloat(raw.price) : null,
    beds: raw.beds ? parseInt(raw.beds, 10) : 1,
    baths: raw.baths ? parseInt(raw.baths, 10) : 1,
    heating: raw.heating || "Central",
    size_m2: raw.size_m2 ? parseInt(raw.size_m2, 10) : null,
    cover_url: raw.cover_url || null,
    amenities: raw.amenities || null,
    description: raw.description || null
  };

  // Frontend validation: title, price required (municipality too if your backend enforces it)
  if (!payload.title || payload.price == null || isNaN(payload.price)) {
    toastr.error("Please fill Title and a valid Price.");
    return;
  }

  const listingId = $("#listingForm").data("listingId");
  const url = listingId
    ? Constants.PROJECT_BASE_URL + "/listings/" + listingId
    : Constants.PROJECT_BASE_URL + "/listings";
  const method = listingId ? "PUT" : "POST";

  $.ajax({
    url: url,
    type: method,
    headers: { "Authentication": localStorage.getItem("user_token") },
    data: JSON.stringify(payload),
    contentType: "application/json",
    success: function () {
      toastr.success(listingId ? "Listing updated." : "Listing created.");
      $("#addListingModal").modal("hide");
      $("#listingForm")[0].reset();
      $("#listingForm").removeData("listingId");
      const user = JSON.parse(localStorage.getItem("user"));
      UserService.loadLandlordListings(user.id);
    },
    error: function (xhr) {
      console.error("Save error JSON:", xhr.status, xhr.responseText);
      // Fallback to form-encoded if JSON fails with 400 and message looks like parsing issue
      if (xhr.status === 400 && /Missing required fields/i.test(xhr.responseText)) {
        $.ajax({
          url: url,
          type: method,
          headers: { "Authentication": localStorage.getItem("user_token") },
          data: $("#listingForm").serialize(),
          success: function () {
            toastr.success(listingId ? "Listing updated." : "Listing created.");
            $("#addListingModal").modal("hide");
            $("#listingForm")[0].reset();
            $("#listingForm").removeData("listingId");
            const user = JSON.parse(localStorage.getItem("user"));
            UserService.loadLandlordListings(user.id);
          },
          error: function (xhr) {
  let message = "Something went wrong.";
  try {
    const json = JSON.parse(xhr.responseText);
    message = json.error || json.message || message;
  } catch (e) {
    message = xhr.responseText || message;
  }
  toastr.error(message);
}
        });
      } else {
        toastr.error(xhr?.responseText || "Failed to save listing.");
      }
    }
  });
},

sendMessage: function () {
  const raw = Object.fromEntries(new FormData(document.getElementById("messageForm")).entries());
    const payload = {
    receiver_id: raw.receiver_id ? parseInt(raw.receiver_id, 10) : null,
    content: raw.content?.trim()
  };

  if (!payload.receiver_id || !payload.content) {
    toastr.error("receiver and message content are required.");
    return;
  }

  $.ajax({
    url: Constants.PROJECT_BASE_URL + "/messages",
    type: "POST",
    headers: { "Authentication": localStorage.getItem("user_token") },
    data: JSON.stringify(payload),
    contentType: "application/json",
    success: function () {
      toastr.success("Message sent.");
      $("#composeMessageModal").modal("hide");
      $("#messageForm")[0].reset();
      const user = JSON.parse(localStorage.getItem("user"));
      UserService.loadInbox(user.id);
    },
    error: function (xhr) {
      let msg = "Failed to send message.";
      try {
        const json = JSON.parse(xhr.responseText);
        msg = json.error || json.message || msg;
      } catch {
        msg = xhr.responseText || msg;
      }
      toastr.error(msg);
    }
  });
},

loadUserReservations: function (userId) {
  $.ajax({
    url: Constants.PROJECT_BASE_URL + "/reservations/user/" + userId,
    type: "GET",
    headers: { "Authentication": localStorage.getItem("user_token") },
    success: function (reservations) {
      if (!Array.isArray(reservations)) {
        $("#activeReservations").text("0");
        $("#pastStays").text("0");
        $("#totalSpent").text("0");
        $("#userReservations").html("<p class='text-muted'>No reservations yet.</p>");
        return;
      }

      const now = new Date();
      let active = 0, past = 0, totalSpent = 0;

      $("#userReservations").empty();

      reservations.forEach(r => {
        const start = new Date(r.start_date);
        const end = new Date(r.end_date);

        if (end >= now) {
          active++;
        } else {
          past++;
        }

        const price = parseFloat(r.total_price);
        totalSpent += isNaN(price) ? 0 : price;

        // Render each reservation
        $("#userReservations").append(`
          <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
            ${r.title} – <span class="text-muted small">${start.toLocaleDateString()} - ${end.toLocaleDateString()}</span>
            <div>
              <button class="btn btn-sm btn-outline-secondary me-1 btn-edit-reservation" data-id="${r.id}">
                <i class="bi bi-pencil"></i> Edit
              </button>
              <button class="btn btn-sm btn-outline-danger btn-cancel-reservation" data-id="${r.id}">
                <i class="bi bi-x-circle"></i> Cancel
              </button>
            </div>
          </li>
        `);
      });

      // Update summary cards
      $("#activeReservations").text(active);
      $("#pastStays").text(past);
      $("#totalSpent").text(totalSpent.toFixed(2));
    },
    error: function () {
      toastr.error("Failed to load reservations.");
    }
  });
},
createReservationFromListing: function (listingId) {
  const raw = Object.fromEntries(new FormData(document.getElementById("newReservationForm")).entries());
  const payload = {
    listings_id: listingId,
    start_date: raw.start_date,
    end_date: raw.end_date,
    guests: raw.guests ? parseInt(raw.guests, 10) : 1,
    status: raw.status || "pending"
  };

  if (!payload.start_date || !payload.end_date) {
    toastr.error("Please select check-in and check-out dates.");
    return;
  }

  $.ajax({
    url: Constants.PROJECT_BASE_URL + "/reservations",
    type: "POST",
    headers: { "Authentication": localStorage.getItem("user_token") },
    data: JSON.stringify(payload),
    contentType: "application/json",
    success: function () {
      toastr.success("Reservation created.");
      $("#newReservationModal").modal("hide");
      $("#newReservationForm")[0].reset();
      const user = JSON.parse(localStorage.getItem("user"));
      UserService.loadUserReservations(user.id);
      UserService.loadUserCalendar(user.id);
    },
    error: function (xhr) {
      console.error("Reservation error:", xhr.status, xhr.responseText);
      toastr.error(xhr?.responseText || "Failed to create reservation.");
    }
  });
},

createReservation: function () {
  const raw = Object.fromEntries(new FormData(document.getElementById("newReservationForm")).entries());
  const payload = {
    listings_id: raw.listings_id ? parseInt(raw.listings_id, 10) : null,
    start_date: raw.start_date,
    end_date: raw.end_date,
    guests: raw.guests ? parseInt(raw.guests, 10) : 1,
    status: raw.status || "pending"
  };

  if (!payload.listings_id || !payload.start_date || !payload.end_date) {
    toastr.error("Please fill all fields.");
    return;
  }

  $.ajax({
    url: Constants.PROJECT_BASE_URL + "/reservations",
    type: "POST",
    headers: { "Authentication": localStorage.getItem("user_token") },
    data: JSON.stringify(payload),
    contentType: "application/json",
    success: function () {
      toastr.success("Reservation created.");
      $("#newReservationModal").modal("hide");
      $("#newReservationForm")[0].reset();
      const user = JSON.parse(localStorage.getItem("user"));
      UserService.loadUserReservations(user.id);
      UserService.loadUserCalendar(user.id);
    },
    error: function (xhr) {
      toastr.error(xhr?.responseText || "Failed to create reservation.");
    }
  });
},
openEditReservation: function (id) {
  if (!id) { toastr.error("Invalid reservation id."); return; }

  $.ajax({
    url: Constants.PROJECT_BASE_URL + "/reservations/" + id,
    type: "GET",
    headers: { "Authentication": localStorage.getItem("user_token") },
    success: function (reservation) {
      $("#editListingTitle").val(reservation.title || "");
      $("#editCheckIn").val(reservation.start_date || "");
      $("#editCheckOut").val(reservation.end_date || "");
      $("#editGuests").val(reservation.guests ?? 1);
      $("#editRequests").val(reservation.special_requests || "");
$("#editStatus").val(reservation.status || "pending");
      $("#editBookingForm").data("reservationId", id);
      $("#editBookingModal").modal("show");
    },
    error: function () {
      toastr.error("Failed to load reservation for edit.");
    }
  });
},

saveReservationChanges: function () {
  const reservationId = $("#editBookingForm").data("reservationId");
  if (!reservationId) { toastr.error("No reservation selected."); return; }

  const raw = Object.fromEntries(new FormData(document.getElementById("editBookingForm")).entries());
  const payload = {
    start_date: raw.start_date,
    end_date: raw.end_date,
    guests: raw.guests ? parseInt(raw.guests, 10) : 1,
    special_requests: raw.special_requests || null,
     status: raw.status || "pending"
  };

  $.ajax({
    url: Constants.PROJECT_BASE_URL + "/reservations/" + reservationId,
    type: "PUT",
    headers: { "Authentication": localStorage.getItem("user_token") },
    data: JSON.stringify(payload),
    contentType: "application/json",
    success: function () {
      toastr.success("Reservation updated.");
      $("#editBookingModal").modal("hide");
      const user = JSON.parse(localStorage.getItem("user"));
      UserService.loadUserReservations(user.id);
      UserService.loadUserCalendar(user.id);
    },
    error: function (xhr) {
      toastr.error(xhr?.responseText || "Failed to update reservation.");
    }
  });
},
cancelReservation: function (id) {
  if (!id) { toastr.error("Invalid reservation id."); return; }

  $.ajax({
    url: Constants.PROJECT_BASE_URL + "/reservations/" + id,
    type: "DELETE",
    headers: { "Authentication": localStorage.getItem("user_token") },
    success: function () {
      toastr.success("Reservation cancelled.");
      $("#cancelBookingModal").modal("hide");
      const user = JSON.parse(localStorage.getItem("user"));
      UserService.loadUserReservations(user.id);
      UserService.loadUserCalendar(user.id);
    },
    error: function () {
      toastr.error("Failed to cancel reservation.");
    }
  });
},

loadUserWishlist: function (userId) {
  $.ajax({
    url: Constants.PROJECT_BASE_URL + "/wishlist/" + userId,
    type: "GET",
    headers: { "Authentication": localStorage.getItem("user_token") },
    success: function (items) {
      if (!Array.isArray(items) || items.length === 0) {
        $("#wishlistGrid").html("<p class='text-muted'>Your wishlist is empty.</p>");
        return;
      }

      $("#wishlistGrid").empty();
      items.forEach(w => {
        $("#wishlistGrid").append(`
          <div class="col">
            <div class="card h-100 shadow-sm">
              <img src="${w.cover_url || 'assets/listings/default.jpg'}" class="card-img-top" alt="Wishlist">
              <div class="card-body">
                <h6 class="card-title mb-1">${w.title}</h6>
                <p class="text-muted small mb-2">${w.price} BAM / night</p>
                <button class="btn btn-sm btn-outline-dark view-listing-btn" data-id="${w.listings_id}">
                  <i class="bi bi-eye"></i> View Listing
                </button>
              </div>
            </div>
          </div>
        `);
      });
    },
    error: function () {
      toastr.error("Failed to load wishlist.");
    }
  });
},
addToWishlist: function () {
  const raw = Object.fromEntries(new FormData(document.getElementById("addWishlistForm")).entries());
  const payload = {
    listings_id: raw.listings_id ? parseInt(raw.listings_id, 10) : null
  };

  if (!payload.listings_id) {
    toastr.error("Please select a listing.");
    return;
  }

  $.ajax({
    url: Constants.PROJECT_BASE_URL + "/wishlist",
    type: "POST",
    headers: { "Authentication": localStorage.getItem("user_token") },
    data: JSON.stringify(payload),
    contentType: "application/json",
    success: function () {
      toastr.success("Added to wishlist.");
      $("#addWishlistModal").modal("hide");
      $("#addWishlistForm")[0].reset();
      const user = JSON.parse(localStorage.getItem("user"));
      UserService.loadUserWishlist(user.id);
    },
    error: function (xhr) {
      toastr.error(xhr?.responseText || "Failed to add to wishlist.");
    }
  });
},
populateListingSelects: function () {
  $.ajax({
    url: Constants.PROJECT_BASE_URL + "/listings",
    type: "GET",
    dataType: "json",
    headers: { "Authentication": localStorage.getItem("user_token") },
    success: function (listings) {
      const $reservationSel = $("#reservationListing");
      const $wishlistSel = $("#wishlistListing");

      // Start with a placeholder
      const placeholder = '<option value="">Choose...</option>';
      $reservationSel.html(placeholder);
      $wishlistSel.html(placeholder);

      if (!Array.isArray(listings) || listings.length === 0 || listings.message) {
        // No listings; keep only the placeholder
        return;
      }

      // Build options safely
      const optionsHtml = listings.map(l => {
        const id = l.id;
        const title = (l.title || "").replace(/</g, "&lt;").replace(/>/g, "&gt;");
        const muni = (l.municipality || "").replace(/</g, "&lt;").replace(/>/g, "&gt;");
        return `<option value="${id}">${title} - ${muni}</option>`;
      }).join("");

      $reservationSel.append(optionsHtml);
      $wishlistSel.append(optionsHtml);
    },
    error: function (xhr) {
      console.error("Populate listings select error:", xhr.status, xhr.responseText);
      // Leave the placeholder to avoid empty select rendering
      $("#reservationListing").html('<option value="">Choose...</option>');
      $("#wishlistListing").html('<option value="">Choose...</option>');
    }
  });
},


loadUserCalendar: function (userId) {
  const el = document.getElementById("calendar");
  if (!el) return;

  $.ajax({
    url: Constants.PROJECT_BASE_URL + "/reservations/user/" + userId,
    type: "GET",
    headers: { "Authentication": localStorage.getItem("user_token") },
    success: function (reservations) {
      if (!Array.isArray(reservations)) return;

      const events = reservations.map(r => ({
        title: r.title,
        start: r.start_date,
        end: r.end_date,
        color: "#007bff" // blue for trips
      }));

      const calendar = new FullCalendar.Calendar(el, {
        initialView: "dayGridMonth",
        events: events
      });
      calendar.render();
    }
  });
},

  login: function (entity) {
    console.log("Sending login request...");
    $.ajax({
      url: Constants.PROJECT_BASE_URL + "/auth/login",
      type: "POST",
      data: JSON.stringify(entity),
      contentType: "application/json",
      dataType: "json",
      success: function (result) {
        console.log("Login success response:", result);
        
        // Check if result.data exists
        if (!result.data || !result.data.token) {
          console.error("No token in response:", result);
          toastr.error("Login failed - no token received");
          return;
        }
        
        localStorage.setItem("user_token", result.data.token);
        console.log("Token saved:", result.data.token);
        
        const decoded = Utils.parseJwt(result.data.token);
        console.log("Decoded token:", decoded);
        
        const user = decoded.user;
        localStorage.setItem("user", JSON.stringify(user));
        console.log("User role:", user?.role);

        if (user?.role === "landlord" || user?.role === Constants.LANDLORD_ROLE) {
          console.log("Redirecting to landlord dashboard");
          window.location.replace("index.html#dashboard_landlord");
        } else if (user?.role === Constants.USER_ROLE || user?.role === "user") {
          console.log("Redirecting to user dashboard");
          window.location.replace("index.html#dashboard_user");
        } else {
          console.log("Unknown role, redirecting to home");
          window.location.replace("index.html#home");
        }
      },
      error: function (xhr) {
        console.error("Login error:", xhr);
        toastr.error(xhr?.responseJSON?.message || xhr?.responseText || "Login failed");
      }
    });
  },

  logout: function () {
    localStorage.clear();
    window.location.replace("index.html#login");
  }
};   
