async function apiGet(url){
  const r = await fetch(url, { credentials:"same-origin" });
  const t = await r.text();
  try { return JSON.parse(t); }
  catch { return { ok:false, error:"No JSON", raw:t }; }
}

async function apiPost(url, fd){
  const r = await fetch(url, { method:"POST", body:fd, credentials:"same-origin" });
  const t = await r.text();
  try { return JSON.parse(t); }
  catch { return { ok:false, error:"No JSON", raw:t }; }
}

const LS_KEY = "todo_tasks_backup_v2";

function esc(s){
  return (s??"").toString().replace(/[&<>"']/g, m => ({
    "&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#039;"
  }[m]));
}

function saveLocal(tasks){ localStorage.setItem(LS_KEY, JSON.stringify(tasks||[])); }
function loadLocal(){ try { return JSON.parse(localStorage.getItem(LS_KEY) || "[]"); } catch { return []; } }

let ALL = [];

function badgePriority(p){
  if(p==="high") return `<span class="badge text-bg-danger">Alta</span>`;
  if(p==="medium") return `<span class="badge text-bg-warning">Media</span>`;
  return `<span class="badge text-bg-secondary">Baja</span>`;
}

function isOverdue(t){
  if(Number(t.is_done)===1) return false;
  if(!t.due_date) return false;
  const today = new Date(); today.setHours(0,0,0,0);
  const d = new Date(t.due_date+"T00:00:00");
  return d < today;
}

function render(){
  const q = (document.querySelector("#search")?.value || "").trim().toLowerCase();
  const list = document.querySelector("#list");
  list.innerHTML = "";

  const filtered = ALL.filter(t => (t.title||"").toLowerCase().includes(q));

  filtered.forEach(t=>{
    const done = Number(t.is_done) === 1;
    const overdue = isOverdue(t);
    const dueTxt = t.due_date ? t.due_date : "—";

    const item = document.createElement("div");
    item.className = "list-group-item bg-transparent text-light d-flex justify-content-between align-items-start";

    item.innerHTML = `
      <div class="d-flex align-items-start gap-2">
        <input class="form-check-input mt-1" type="checkbox" ${done?"checked":""} data-id="${t.id}">
        <div class="${done ? "task-done" : ""}">
          <div class="fw-semibold">
            ${esc(t.title)}
            ${overdue ? `<span class="badge text-bg-danger ms-2">Vencida</span>` : ``}
          </div>

          <div class="text-secondary small mt-1">
            ${badgePriority(t.priority)}
            <span class="ms-2">📅 ${esc(dueTxt)}</span>
          </div>

          <div class="text-secondary small">${esc(t.created_at || "")}</div>
        </div>
      </div>

      <button class="btn btn-sm btn-outline-danger" data-del="${t.id}">Eliminar</button>
    `;

    list.appendChild(item);
  });

  document.querySelector("#count").textContent =
    `Total cargadas: ${ALL.length} | Mostrando: ${filtered.length}`;
}

function setStats(st){
  document.querySelector("#stTotal").textContent = st.total ?? 0;
  document.querySelector("#stPending").textContent = st.pending ?? 0;
  document.querySelector("#stDone").textContent = st.done ?? 0;
  document.querySelector("#stOverdue").textContent = st.overdue ?? 0;
  document.querySelector("#stToday").textContent = st.due_today ?? 0;
}

async function loadStats(){
  const data = await apiGet("../api/tasks_stats.php");
  if(data.ok) setStats(data.stats || {});
}

function filters(){
  return {
    q: (document.querySelector("#search").value || "").trim(),
    status: document.querySelector("#f_status").value,
    priority: document.querySelector("#f_priority").value,
    due: document.querySelector("#f_due").value
  };
}

async function loadTasks(){
  const msg = document.querySelector("#msg");
  msg.textContent = "Cargando...";

  const f = filters();
  const url =
    `../api/tasks_list.php?q=${encodeURIComponent(f.q)}` +
    `&status=${encodeURIComponent(f.status)}` +
    `&priority=${encodeURIComponent(f.priority)}` +
    `&due=${encodeURIComponent(f.due)}`;

  const data = await apiGet(url);

  if(data.ok){
    ALL = data.tasks || [];
    saveLocal(ALL);
    msg.textContent = "OK ✅";
  }else{
    ALL = loadLocal();
    msg.textContent = "Backend no disponible. Usando LocalStorage (modo offline).";
  }

  render();
  loadStats();
}

async function addTask(title, priority, due_date){
  const fd = new FormData();
  fd.append("title", title);
  fd.append("priority", priority);
  fd.append("due_date", due_date || "");

  const data = await apiPost("../api/tasks_create.php", fd);
  if(data.ok){
    await loadTasks();
    return true;
  }

  // offline fallback
  const local = loadLocal();
  local.unshift({
    id: Date.now(),
    title,
    priority,
    due_date: due_date || null,
    is_done: 0,
    created_at: new Date().toISOString().slice(0,19).replace("T"," ")
  });
  saveLocal(local);
  ALL = local;
  render();
  alert("Guardado en LocalStorage (offline).");
  return true;
}

async function toggleTask(id){
  const fd = new FormData();
  fd.append("id", id);

  const data = await apiPost("../api/tasks_toggle.php", fd);
  if(data.ok){ await loadTasks(); return; }

  // offline
  const local = loadLocal().map(t => t.id==id ? { ...t, is_done: t.is_done?0:1 } : t);
  saveLocal(local);
  ALL = local;
  render();
  loadStats();
}

async function deleteTask(id){
  if(!confirm("¿Eliminar esta tarea?")) return;

  const fd = new FormData();
  fd.append("id", id);

  const data = await apiPost("../api/tasks_delete.php", fd);
  if(data.ok){ await loadTasks(); return; }

  const local = loadLocal().filter(t => t.id!=id);
  saveLocal(local);
  ALL = local;
  render();
  loadStats();
}

window.addEventListener("load", ()=>{
  document.querySelector("#btnReload").addEventListener("click", loadTasks);

  document.querySelector("#search").addEventListener("input", ()=> loadTasks());
  document.querySelector("#f_status").addEventListener("change", ()=> loadTasks());
  document.querySelector("#f_priority").addEventListener("change", ()=> loadTasks());
  document.querySelector("#f_due").addEventListener("change", ()=> loadTasks());

  document.querySelector("#formAdd").addEventListener("submit", async (e)=>{
    e.preventDefault();
    const title = document.querySelector("#title").value.trim();
    const priority = document.querySelector("#priority").value;
    const due_date = document.querySelector("#due_date").value;

    if(!title) return;

    await addTask(title, priority, due_date);
    document.querySelector("#title").value = "";
    document.querySelector("#priority").value = "medium";
    document.querySelector("#due_date").value = "";
    document.querySelector("#title").focus();
  });

  document.querySelector("#list").addEventListener("click", (e)=>{
    const del = e.target?.getAttribute("data-del");
    if(del) deleteTask(del);
  });

  document.querySelector("#list").addEventListener("change", (e)=>{
    const id = e.target?.getAttribute("data-id");
    if(id) toggleTask(id);
  });

  loadTasks();
});
