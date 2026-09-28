<?php
include '../includes/header.php';
?>
<div class="app">
  <aside class="sidebar">
    <div class="brand">
      <span class="brand-mark">T</span><span class="brand-name">Dashboard</span>
    </div>
    <nav class="nav">
      <button class="nav-btn active" data-view="home">
        <svg
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          stroke-width="2">
          <path d="M3 11l9-7 9 7" />
          <path d="M5 10v10h14V10" />
        </svg>
        <span class="label">Home Page</span>
      </button>
      <button class="nav-btn" data-view="posts">
        <svg
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          stroke-width="2">
          <rect x="3" y="4" width="18" height="16" rx="2" />
          <path d="M7 9h10M7 13h10M7 17h6" />
        </svg>
        <span class="label">Blog Posts</span>
      </button>
      <button class="nav-btn" data-view="new">
        <svg
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          stroke-width="2">
          <path d="M12 5v14M5 12h14" />
        </svg>
        <span class="label">New Post</span>
      </button>
    </nav>
    <div class="sidebar-foot text-danger d-flex align-items-center gap-2">
      <svg
        xmlns="http://www.w3.org/2000/svg"
        width="24"
        height="24"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
        class="icon icon-tabler icons-tabler-outline icon-tabler-power">
        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
        <path d="M7 6a7.75 7.75 0 1 0 10 0" />
        <path d="M12 4l0 8" />
      </svg>
      <a href="" class="text-danger"> Log out</a>
    </div>
  </aside>

  <main class="content">
    <div class="topbar">
      <div>
        <h1 id="page-title">Home Page</h1>
        <p id="page-sub">What visitors see first on your portfolio.</p>
      </div>
    </div>

    <!-- HOME VIEW -->
    <section class="view active" id="view-home">
      <div class="panel">
        <div class="field">
          <label for="h-title">Hero title</label>
          <input
            type="text"
            id="h-title"
            placeholder="e.g. Hi, I'm Ada — I build things." />
        </div>
        <div class="field">
          <label for="h-subtitle">Hero subtitle</label>
          <input
            type="text"
            id="h-subtitle"
            placeholder="e.g. Product designer & frontend developer" />
        </div>
        <div class="field">
          <label for="h-bio">About / bio</label>
          <textarea
            id="h-bio"
            placeholder="A couple of sentences about you."></textarea>
        </div>
        <div class="field">
          <label for="h-avatar">Avatar image URL</label>
          <input
            type="url"
            id="h-avatar"
            placeholder="https://example.com/avatar.jpg" />
        </div>
        <div class="form-actions">
          <button class="btn btn-primary" id="save-home">
            Save home page
          </button>
        </div>
      </div>
    </section>

    <!-- POSTS VIEW -->
    <section class="view" id="view-posts">
      <div class="panel">
        <div id="posts-list"></div>
      </div>
    </section>

    <!-- NEW / EDIT POST VIEW -->
    <section class="view" id="view-new">
      <div class="panel">
        <div class="field">
          <label for="p-title">Post title</label>
          <input
            type="text"
            id="p-title"
            placeholder="e.g. Redesigning my portfolio in a weekend" />
        </div>
        <div class="field">
          <label for="p-slug">URL slug</label>
          <input
            type="text"
            id="p-slug"
            class="slug"
            placeholder="redesigning-my-portfolio" />
        </div>
        <div class="field">
          <label for="p-excerpt">Excerpt</label>
          <input
            type="text"
            id="p-excerpt"
            placeholder="One line summary for the posts list" />
        </div>
        <div class="field">
          <label for="p-content">Content</label>
          <textarea
            id="p-content"
            style="min-height: 160px"
            placeholder="Write your post..."></textarea>
        </div>
        <div class="field">
          <label for="p-tags">Tags (comma separated)</label>
          <input type="text" id="p-tags" placeholder="design, process" />
        </div>
        <div class="checkbox-row">
          <input type="checkbox" id="p-published" />
          <label for="p-published" style="margin: 0">Publish immediately</label>
        </div>
        <div class="form-actions">
          <button class="btn btn-primary" id="save-post">Save post</button>
          <button
            class="btn btn-ghost"
            id="cancel-edit"
            style="display: none">
            Cancel edit
          </button>
        </div>
      </div>
    </section>
  </main>
</div>

<div class="toast" id="toast"></div>

<!-- lenis js -->
<script>
  (function() {
    const STORE_HOME = "cms_home_v1";
    const STORE_POSTS = "cms_posts_v1";

    function load(key, fallback) {
      try {
        const raw = localStorage.getItem(key);
        return raw ? JSON.parse(raw) : fallback;
      } catch (e) {
        return fallback;
      }
    }

    function save(key, value) {
      try {
        localStorage.setItem(key, JSON.stringify(value));
      } catch (e) {}
    }

    let home = load(STORE_HOME, {
      title: "",
      subtitle: "",
      bio: "",
      avatar: "",
    });
    let posts = load(STORE_POSTS, []);
    let editingId = null;

    const $ = (id) => document.getElementById(id);
    const views = {
      home: $("view-home"),
      posts: $("view-posts"),
      new: $("view-new"),
    };
    const titles = {
      home: ["Home Page", "What visitors see first on your portfolio."],
      posts: [
        "Blog Posts",
        "Everything you\u2019ve written, in one place.",
      ],
      new: ["New Post", "Draft it here, publish when ready."],
    };

    function showToast(msg) {
      const t = $("toast");
      t.textContent = msg;
      t.classList.add("show");
      clearTimeout(showToast._t);
      showToast._t = setTimeout(() => t.classList.remove("show"), 1800);
    }

    function switchView(name) {
      Object.keys(views).forEach((k) =>
        views[k].classList.toggle("active", k === name),
      );
      document
        .querySelectorAll(".nav-btn")
        .forEach((b) =>
          b.classList.toggle("active", b.dataset.view === name),
        );
      $("page-title").textContent = titles[name][0];
      $("page-sub").textContent = titles[name][1];
      if (name !== "new" && editingId !== null) resetPostForm();
    }

    document.querySelectorAll(".nav-btn").forEach((btn) => {
      btn.addEventListener("click", () => switchView(btn.dataset.view));
    });

    // ---- Home form ----
    $("h-title").value = home.title;
    $("h-subtitle").value = home.subtitle;
    $("h-bio").value = home.bio;
    $("h-avatar").value = home.avatar;

    $("save-home").addEventListener("click", () => {
      home = {
        title: $("h-title").value.trim(),
        subtitle: $("h-subtitle").value.trim(),
        bio: $("h-bio").value.trim(),
        avatar: $("h-avatar").value.trim(),
      };
      save(STORE_HOME, home);
      showToast("Home page saved");
    });

    // ---- Posts list ----
    function slugify(str) {
      return str
        .toLowerCase()
        .trim()
        .replace(/[^a-z0-9]+/g, "-")
        .replace(/(^-|-$)/g, "");
    }

    function renderPosts() {
      const list = $("posts-list");
      if (!posts.length) {
        list.innerHTML =
          '<div class="empty-state">No posts yet. Create your first one from "New Post".</div>';
        return;
      }
      list.innerHTML = posts
        .slice()
        .reverse()
        .map(
          (p) => `
      <div class="post-row" data-id="${p.id}">
        <div>
          <div class="post-title">${escapeHtml(p.title)}${p.published ? '<span class="badge live">Published</span>' : '<span class="badge">Draft</span>'}</div>
          <div class="post-meta">${p.date} &middot; /${p.slug}</div>
          ${p.excerpt ? `<div class="post-excerpt">${escapeHtml(p.excerpt)}</div>` : ""}
        </div>
        <div class="post-actions">
          <button class="icon-btn edit-post" title="Edit" aria-label="Edit post">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 013 3L7 19l-4 1 1-4 12.5-12.5z"/></svg>
          </button>
          <button class="icon-btn delete-post" title="Delete" aria-label="Delete post">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M8 6V4h8v2M19 6l-1 14H6L5 6"/></svg>
          </button>
        </div>
      </div>
    `,
        )
        .join("");

      list.querySelectorAll(".edit-post").forEach((btn) => {
        btn.addEventListener("click", (e) => {
          const id = e.target.closest(".post-row").dataset.id;
          const post = posts.find((p) => String(p.id) === id);
          if (post) loadPostIntoForm(post);
        });
      });
      list.querySelectorAll(".delete-post").forEach((btn) => {
        btn.addEventListener("click", (e) => {
          const id = e.target.closest(".post-row").dataset.id;
          if (confirm("Delete this post? This can\u2019t be undone.")) {
            posts = posts.filter((p) => String(p.id) !== id);
            save(STORE_POSTS, posts);
            renderPosts();
            showToast("Post deleted");
          }
        });
      });
    }

    function escapeHtml(str) {
      const d = document.createElement("div");
      d.textContent = str;
      return d.innerHTML;
    }

    // ---- New / edit post form ----
    let slugTouched = false;
    $("p-title").addEventListener("input", () => {
      if (!slugTouched) $("p-slug").value = slugify($("p-title").value);
    });
    $("p-slug").addEventListener("input", () => {
      slugTouched = true;
    });

    function loadPostIntoForm(post) {
      editingId = post.id;
      $("p-title").value = post.title;
      $("p-slug").value = post.slug;
      $("p-excerpt").value = post.excerpt;
      $("p-content").value = post.content;
      $("p-tags").value = (post.tags || []).join(", ");
      $("p-published").checked = !!post.published;
      slugTouched = true;
      $("cancel-edit").style.display = "inline-flex";
      switchView("new");
    }

    function resetPostForm() {
      editingId = null;
      slugTouched = false;
      ["p-title", "p-slug", "p-excerpt", "p-content", "p-tags"].forEach(
        (id) => ($(id).value = ""),
      );
      $("p-published").checked = false;
      $("cancel-edit").style.display = "none";
    }

    $("cancel-edit").addEventListener("click", () => {
      resetPostForm();
      switchView("posts");
    });

    $("save-post").addEventListener("click", () => {
      const title = $("p-title").value.trim();
      if (!title) {
        showToast("Give the post a title first");
        return;
      }
      const data = {
        title,
        slug: $("p-slug").value.trim() || slugify(title),
        excerpt: $("p-excerpt").value.trim(),
        content: $("p-content").value,
        tags: $("p-tags")
          .value.split(",")
          .map((t) => t.trim())
          .filter(Boolean),
        published: $("p-published").checked,
        date: new Date().toISOString().slice(0, 10),
      };
      if (editingId !== null) {
        posts = posts.map((p) =>
          p.id === editingId ? {
            ...p,
            ...data
          } : p,
        );
        showToast("Post updated");
      } else {
        data.id = Date.now();
        posts.push(data);
        showToast("Post saved");
      }
      save(STORE_POSTS, posts);
      resetPostForm();
      renderPosts();
      switchView("posts");
    });

    renderPosts();
  })();
</script>
<?php
include '../includes/script.php';
?>