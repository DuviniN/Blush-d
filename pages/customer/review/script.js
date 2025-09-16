// review/script.js
// Must be loaded on pages where product.php runs (we instruct to add it to navbar.php).
document.addEventListener("DOMContentLoaded", function () {
  // Inject CSS if not already present
  if (!document.querySelector('link[href="../review/styles.css"]')) {
    const link = document.createElement("link");
    link.rel = "stylesheet";
    link.href = "../review/styles.css";
    document.head.appendChild(link);
  }

  const reviewsTabBtn = document.querySelector(
    '.tab-button[data-tab="reviews"]'
  );
  if (!reviewsTabBtn) return; // page doesn't have tabs or different structure

  // Load reviews when tab clicked (or on load if tab is active)
  reviewsTabBtn.addEventListener("click", () => {
    loadReviews();
  });
  if (reviewsTabBtn.classList.contains("active")) loadReviews();

  function getProductId() {
    const params = new URLSearchParams(window.location.search);
    return params.get("id");
  }

  async function loadReviews() {
    const productId = getProductId();
    const container = document.getElementById("reviews");
    if (!container) return;
    container.innerHTML = '<div class="rv-loading">Loading reviews...</div>';
    try {
      const res = await fetch(
        `../review/fetch_reviews.php?product_id=${productId}`
      );
      const data = await res.json();
      if (!data.success) {
        container.innerHTML =
          '<div class="rv-error">Could not load reviews.</div>';
        return;
      }
      container.innerHTML = renderReviewsUI(data);
      attachFormHandlers();
    } catch (e) {
      container.innerHTML =
        '<div class="rv-error">Network error while loading reviews.</div>';
      console.error(e);
    }
  }

  function renderReviewsUI(data) {
    const avg = data.avg || 0;
    const count = data.count || 0;
    let html = `
      <div class="reviews-aggregate">
        <div class="avg-rating">${avg}</div>
        <div class="star-display">${renderStars(avg)}</div>
        <div class="count">${count} review${count !== 1 ? "s" : ""}</div>
      </div>
      <div class="reviews-list">`;

    if (!data.reviews || data.reviews.length === 0) {
      html +=
        '<div class="no-reviews">No reviews yet. Be the first to review!</div>';
    } else {
      data.reviews.forEach((r) => {
        const name = `${escapeHtml(r.first_name || "Anonymous")} ${escapeHtml(
          r.last_name || ""
        )}`.trim();
        html += `
          <div class="review-item">
            <div style="display:flex;justify-content:space-between;align-items:center">
              <div>
                <div class="review-user">${name}</div>
                <div class="review-stars">${renderStars(r.rating)}</div>
              </div>
              <div class="review-date">${formatDate(r.review_date)}</div>
            </div>
            <div class="review-comments">${escapeHtml(r.comments || "")}</div>
          </div>`;
      });
    }

    html += `</div>
      <div class="review-form-wrapper">
        <h3>Write a review</h3>
        <form id="reviewForm">
          <div class="star-input" id="starInput">
            ${[1, 2, 3, 4, 5]
              .map(
                (i) =>
                  `<span class="star" data-value="${i}" title="${i}">${
                    i <= 0 ? "☆" : "☆"
                  }</span>`
              )
              .join("")}
          </div>
          <input type="hidden" name="rating" id="ratingInput" value="0">
          <textarea name="comments" id="commentsInput" rows="4" placeholder="Write your review..."></textarea>
          <button type="submit" class="btn">Submit Review</button>
        </form>
        <div id="reviewMessage" class="review-message"></div>
      </div>`;
    return html;
  }

  function renderStars(score) {
    const full = Math.floor(score);
    const half = score - full >= 0.5;
    let s = "";
    for (let i = 1; i <= 5; i++) {
      if (i <= full) s += '<span class="star-icon filled">&#9733;</span>';
      else if (i === full + 1 && half)
        s += '<span class="star-icon half">&#9733;</span>';
      else s += '<span class="star-icon">&#9734;</span>';
    }
    return s;
  }

  function escapeHtml(str) {
    if (!str) return "";
    return String(str).replace(/[&<>"']/g, function (m) {
      return {
        "&": "&amp;",
        "<": "&lt;",
        ">": "&gt;",
        '"': "&quot;",
        "'": "&#039;",
      }[m];
    });
  }

  function formatDate(dt) {
    if (!dt) return "";
    const d = new Date(dt);
    if (isNaN(d.getTime())) return dt;
    return d.toLocaleString();
  }

  function attachFormHandlers() {
    const stars = document.querySelectorAll("#starInput .star");
    const ratingInput = document.getElementById("ratingInput");
    const commentsInput = document.getElementById("commentsInput");
    const form = document.getElementById("reviewForm");
    const message = document.getElementById("reviewMessage");
    let selected = 0;

    stars.forEach((s) => {
      s.addEventListener("mouseover", () => {
        highlightStars(parseInt(s.dataset.value));
      });
      s.addEventListener("mouseout", () => {
        highlightStars(selected);
      });
      s.addEventListener("click", () => {
        selected = parseInt(s.dataset.value);
        ratingInput.value = selected;
        highlightStars(selected);
      });
    });

    function highlightStars(n) {
      stars.forEach((st) => {
        if (parseInt(st.dataset.value) <= n) st.classList.add("on");
        else st.classList.remove("on");
      });
    }

    form.addEventListener("submit", async (e) => {
      e.preventDefault();
      message.textContent = "";
      const rating = parseInt(ratingInput.value);
      const comments = commentsInput.value.trim();
      if (!rating || rating < 1 || rating > 5) {
        message.textContent = "Please select rating (1-5 stars).";
        message.className = "review-message review-error";
        return;
      }
      const productId = getProductId();
      const fd = new FormData();
      fd.append("product_id", productId);
      fd.append("rating", rating);
      fd.append("comments", comments);

      try {
        const res = await fetch("../review/submit_review.php", {
          method: "POST",
          body: fd,
        });
        const data = await res.json();
        if (data.success) {
          message.textContent = "Thank you — your review was submitted.";
          message.className = "review-message review-success";
          commentsInput.value = "";
          ratingInput.value = "0";
          selected = 0;
          highlightStars(0);
          // reload reviews
          setTimeout(loadReviews, 700);
        } else if (data.message === "not_logged_in") {
          message.innerHTML =
            'You must <a href="../login/login.php">log in</a> to submit a review.';
          message.className = "review-message review-error";
        } else {
          message.textContent = data.message || "Could not submit review.";
          message.className = "review-message review-error";
        }
      } catch (err) {
        console.error(err);
        message.textContent = "Network error.";
        message.className = "review-message review-error";
      }
    });
  }
});
