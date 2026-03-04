@extends('seller.layout.app')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&family=DM+Sans:wght@400;500;600;700;800&display=swap');
        :root {
            --bg:#f4f6f9; --surface:#fff; --surface-2:#f8fafc; --surface-3:#f1f4f8;
            --border:#e2e8f0; --border-s:#edf0f5;
            --text:#0f172a; --text-sub:#475569; --text-muted:#94a3b8;
            --emerald:#10b981; --emerald-lt:#ecfdf5; --emerald-bd:rgba(16,185,129,.25);
            --amber:#f59e0b; --amber-lt:#fffbeb; --amber-bd:rgba(245,158,11,.25);
            --sky:#0284c7; --sky-lt:#f0f9ff; --sky-bd:rgba(2,132,199,.25);
            --rose:#ef4444; --rose-lt:#fef2f2; --rose-bd:rgba(239,68,68,.25);
            --violet:#8b5cf6; --violet-lt:#f5f3ff; --violet-bd:rgba(139,92,246,.25);
            --primary:#3b82f6; --primary-dark:#2563eb; --primary-lt:#eff6ff;
            --sh-sm:0 1px 3px rgba(15,23,42,.06),0 1px 2px rgba(15,23,42,.04);
            --sh:0 4px 12px rgba(15,23,42,.08),0 1px 3px rgba(15,23,42,.05);
            --r:10px; --r-xl:18px;
        }
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        .dc-page{background:var(--bg);font-family:'Plus Jakarta Sans',sans-serif;min-height:100vh;padding:28px 24px 56px}
        .dc-wrap{max-width:1600px;animation:up .35s cubic-bezier(.16,1,.3,1) both}
        @keyframes up{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:translateY(0)}}

        /* ── Header ── */
        .dc-hdr{display:flex;align-items:flex-start;justify-content:space-between;gap:20px;flex-wrap:wrap;margin-bottom:20px}
        .dc-crumb{display:flex;align-items:center;gap:5px;font-size:12px;color:var(--text-muted);margin-bottom:7px;font-weight:500}
        .dc-title{font-size:22px;font-weight:800;color:var(--text);letter-spacing:-.5px;display:flex;align-items:center;gap:10px}
        .dc-tbadge{font-family:'JetBrains Mono',monospace;font-size:11px;padding:3px 10px;border-radius:20px;background:var(--emerald-lt);color:var(--emerald);border:1px solid var(--emerald-bd)}
        .dc-sub{display:flex;align-items:center;gap:6px;margin-top:5px;font-size:13px;color:var(--text-sub)}
        .dc-tabs{display:flex;align-items:center;gap:4px;background:var(--surface);border:1px solid var(--border);border-radius:var(--r);padding:4px;box-shadow:var(--sh-sm)}
        .dc-tab{font-size:12.5px;font-weight:600;padding:7px 18px;border-radius:7px;border:1px solid transparent;color:var(--text-sub);text-decoration:none;transition:all .16s;white-space:nowrap}
        .dc-tab:hover{background:var(--surface-3);color:var(--text)}
        .dc-tab.active{background:var(--primary);color:#fff;box-shadow:0 2px 8px rgba(59,130,246,.28)}

        /* ── Alerts ── */
        .dc-alert{display:flex;align-items:flex-start;gap:10px;padding:12px 16px;border-radius:var(--r);font-size:13.5px;margin-bottom:14px;border:1px solid;font-weight:500}
        .dc-alert-ok{background:var(--emerald-lt);border-color:var(--emerald-bd);color:var(--emerald)}
        .dc-alert-err{background:var(--rose-lt);border-color:var(--rose-bd);color:var(--rose)}

        /* ── Stats Grid ── */
        .stats-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px}
        .stat-card{background:var(--surface);border-radius:12px;padding:20px;box-shadow:0 2px 8px rgba(0,0,0,.05);border-left:4px solid transparent;cursor:pointer;transition:transform .2s}
        .stat-card:hover{transform:translateY(-2px);box-shadow:0 4px 12px rgba(0,0,0,.1)}
        .stat-card.pending{border-left-color:var(--amber)}
        .stat-card.packing{border-left-color:var(--primary)}
        .stat-card.packed{border-left-color:var(--violet)}
        .stat-card.givedriver{border-left-color:var(--emerald)}
        .stat-value{font-size:28px;font-weight:800;color:var(--text);line-height:1.2}
        .stat-label{font-size:12px;color:var(--text-muted);font-weight:600;text-transform:uppercase;letter-spacing:.5px}

        /* ── Business Banner ── */
        .biz-banner{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;
            padding:12px 18px;background:var(--surface);border:1px solid var(--border);
            border-radius:var(--r);box-shadow:var(--sh-sm);margin-bottom:16px}
        .biz-banner-l{display:flex;align-items:center;gap:12px;flex-wrap:wrap}
        .biz-ico{width:34px;height:34px;border-radius:8px;background:var(--violet-lt);border:1px solid var(--violet-bd);
            display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0}
        .biz-label{font-size:11px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.7px;margin-bottom:2px}
        .biz-name{font-size:14px;font-weight:800;color:var(--text);letter-spacing:-.3px}
        .biz-id{font-family:'JetBrains Mono',monospace;font-size:11px;color:var(--text-muted);background:var(--surface-3);
            padding:2px 7px;border-radius:4px;border:1px solid var(--border);margin-left:6px}
        .biz-form{display:flex;align-items:center;gap:8px}
        .biz-select{border:1.5px solid var(--border);border-radius:8px;padding:7px 10px;
            font-family:'Plus Jakarta Sans',sans-serif;font-size:13px;font-weight:600;color:var(--text);
            background:var(--surface);outline:none;cursor:pointer;min-width:220px;
            transition:border-color .15s,box-shadow .15s}
        .biz-select:focus{border-color:var(--violet);box-shadow:0 0 0 3px var(--violet-bd)}
        .biz-no-data{font-size:12.5px;color:var(--text-muted);font-style:italic}

        /* ── Bulk Actions ── */
        .bulk-actions{background:var(--surface);border-radius:12px;padding:16px 20px;margin-bottom:20px;
            border:2px solid var(--primary);box-shadow:0 4px 12px rgba(59,130,246,.15);
            display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px}
        .bulk-actions-left{display:flex;align-items:center;gap:16px;flex-wrap:wrap}
        .selected-count{background:var(--primary);color:#fff;padding:4px 12px;border-radius:30px;
            font-size:13px;font-weight:600}
        .bulk-origin-bar{display:none;background:var(--amber-lt);border-radius:12px;padding:16px 20px;
            margin-bottom:20px;border:2px solid var(--amber);align-items:center;gap:16px;flex-wrap:wrap}
        .bulk-origin-bar.show{display:flex}
        .origin-input-group{flex:1;min-width:250px;position:relative}
        .origin-input-group input{width:100%;padding:10px 14px;border:2px solid var(--border);
            border-radius:8px;font-size:14px;font-family:'Plus Jakarta Sans',sans-serif}
        .origin-input-group input:focus{border-color:var(--amber);outline:none}
        .city-dropdown{position:absolute;top:100%;left:0;right:0;background:var(--surface);
            border:2px solid var(--primary);border-radius:8px;max-height:200px;overflow-y:auto;
            z-index:1000;display:none}
        .city-dropdown.open{display:block}
        .city-item{padding:10px 14px;cursor:pointer;border-bottom:1px solid var(--border);
            display:flex;justify-content:space-between}
        .city-item:hover{background:var(--primary-lt)}
        .city-item .city-name{font-weight:600;color:var(--text)}
        .city-item .city-state{font-size:11px;color:var(--text-muted)}

        /* ── Card ── */
        .dc-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r-xl);box-shadow:var(--sh);overflow:hidden}
        .dc-chead{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;padding:16px 22px;border-bottom:1px solid var(--border-s);background:var(--surface-2)}
        .dc-cbody{padding:20px 22px}
        .dc-sf{display:flex;gap:8px;flex-wrap:wrap;align-items:center}
        .dc-sw{position:relative}
        .dc-sw svg{position:absolute;left:11px;top:50%;transform:translateY(-50%);color:var(--text-muted);pointer-events:none}
        .dc-si{background:var(--surface);border:1px solid var(--border);border-radius:8px;color:var(--text);font-family:'Plus Jakarta Sans',sans-serif;font-size:13px;padding:8px 12px 8px 34px;width:300px;outline:none;transition:border-color .16s;box-shadow:var(--sh-sm)}
        .dc-si:focus{border-color:var(--primary);box-shadow:0 0 0 3px rgba(59,130,246,.12)}
        .dc-tpill{font-size:12px;font-weight:600;color:var(--text-sub);background:var(--surface);padding:4px 14px;border-radius:20px;border:1px solid var(--border);font-family:'JetBrains Mono',monospace}

        /* ── Buttons ── */
        .btn-dc{font-family:'Plus Jakarta Sans',sans-serif;font-size:13px;font-weight:600;padding:8px 18px;border-radius:8px;border:1px solid transparent;cursor:pointer;transition:all .16s;text-decoration:none;display:inline-flex;align-items:center;gap:6px;line-height:1}
        .btn-em{background:var(--emerald);color:#fff;box-shadow:0 1px 4px rgba(16,185,129,.3)}
        .btn-em:hover{background:#059669;color:#fff}
        .btn-ghost{background:var(--surface);border-color:var(--border);color:var(--text-sub);box-shadow:var(--sh-sm)}
        .btn-ghost:hover{background:var(--surface-3);color:var(--text)}
        .btn-violet{background:var(--violet);color:#fff;box-shadow:0 1px 4px rgba(139,92,246,.3)}
        .btn-violet:hover{background:#7c3aed;color:#fff}
        .btn-primary{background:var(--primary);color:#fff}
        .btn-primary:hover{background:var(--primary-dark);color:#fff}
        .btn-warning{background:var(--amber);color:#fff}
        .btn-warning:hover{background:#d97706;color:#fff}
        .btn-bulk{background:var(--emerald-lt);border-color:var(--emerald-bd);color:var(--emerald)}
        .btn-bulk:hover{background:var(--emerald);color:#fff;border-color:var(--emerald)}
        .btn-bulk:disabled{opacity:.45;cursor:not-allowed}
        .btn-dispatch{background:var(--sky);border-color:rgba(2,132,199,.3);color:#fff}
        .btn-dispatch:hover{background:#0369a1;color:#fff}
        .action-btn{width:34px;height:34px;border-radius:8px;border:none;display:inline-flex;align-items:center;justify-content:center;cursor:pointer;transition:all .2s;margin:0 2px;text-decoration:none}
        .action-btn.view{background:var(--primary);color:#fff}
        .action-btn.next{background:var(--violet);color:#fff}
        .action-btn.print{background:var(--amber);color:#fff}
        .action-btn:hover{transform:translateY(-2px);box-shadow:0 4px 12px rgba(0,0,0,.15)}
        .btn-sm-view{font-size:12px;font-weight:600;padding:5px 10px;border-radius:7px;border:1px solid var(--sky-bd);background:var(--sky-lt);color:var(--sky);cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;transition:all .15s}
        .btn-sm-view:hover{background:var(--sky);color:#fff;border-color:var(--sky)}
        .btn-sm-next{font-size:12px;font-weight:600;padding:5px 10px;border-radius:7px;border:1px solid var(--emerald-bd);background:var(--emerald-lt);color:var(--emerald);cursor:pointer;display:inline-flex;align-items:center;transition:all .15s}
        .btn-sm-next:hover{background:var(--emerald);color:#fff;border-color:var(--emerald)}
        .btn-sm-dispatch{font-size:12px;font-weight:700;padding:5px 10px;border-radius:7px;border:1px solid rgba(2,132,199,.3);background:var(--sky-lt);color:var(--sky);cursor:pointer;display:inline-flex;align-items:center;transition:all .15s}
        .btn-sm-dispatch:hover{background:var(--sky);color:#fff}
        .btn-sm-final{font-size:12px;font-weight:600;padding:5px 10px;border-radius:7px;border:1px solid var(--border);background:var(--surface-3);color:var(--text-muted);pointer-events:none;display:inline-flex;align-items:center}
        .btn-print-label{font-size:12px;font-weight:600;padding:5px 10px;border-radius:7px;
            border:1px solid rgba(245,158,11,.4);background:var(--amber-lt);color:#92400e;
            cursor:pointer;display:inline-flex;align-items:center;gap:4px;transition:all .15s;white-space:nowrap}
        .btn-print-label:hover{background:var(--amber);color:#fff;border-color:var(--amber)}

        /* ── Checkboxes ── */
        input[type=checkbox].chk-main,input[type=checkbox].rowCheck{appearance:none;-webkit-appearance:none;width:16px;height:16px;border-radius:5px;border:1.5px solid var(--border);background:var(--surface);cursor:pointer;position:relative;transition:all .14s;flex-shrink:0;vertical-align:middle}
        input[type=checkbox].chk-main:checked,input[type=checkbox].rowCheck:checked{background:var(--primary);border-color:var(--primary)}
        input[type=checkbox].chk-main:checked::after,input[type=checkbox].rowCheck:checked::after{content:'';position:absolute;left:4px;top:1.5px;width:5px;height:9px;border:2px solid #fff;border-top:none;border-left:none;transform:rotate(45deg)}

        /* ── Table ── */
        .dc-twrap{border-radius:var(--r);overflow:hidden;border:1px solid var(--border)}
        table.dc-t{width:100%;border-collapse:collapse;font-size:13px}
        table.dc-t thead{background:var(--surface-2);border-bottom:2px solid var(--border)}
        table.dc-t thead th{font-size:11px;font-weight:700;letter-spacing:.7px;text-transform:uppercase;color:var(--text-muted);padding:11px 13px;white-space:nowrap;text-align:left;border-right:1px solid var(--border-s)}
        table.dc-t thead th:last-child{border-right:none}
        table.dc-t tbody tr{border-bottom:1px solid var(--border-s);transition:background .1s}
        table.dc-t tbody tr:last-child{border-bottom:none}
        table.dc-t tbody tr:hover{background:var(--primary-lt)}
        table.dc-t tbody td{padding:11px 13px;color:var(--text);vertical-align:middle;border-right:1px solid var(--border-s)}
        table.dc-t tbody td:last-child{border-right:none}
        table.dc-t tfoot tr{background:var(--surface-2);border-top:2px solid var(--border)}
        table.dc-t tfoot td{padding:10px 13px;font-family:'JetBrains Mono',monospace}

        /* ── Cell styles ── */
        .c-id{font-family:'JetBrains Mono',monospace;font-size:11px;color:var(--text-muted)}
        .c-call{font-family:'JetBrains Mono',monospace;font-size:12.5px;font-weight:500;color:var(--primary)}
        .c-oc{font-family:'JetBrains Mono',monospace;font-size:11px;background:var(--amber-lt);padding:2px 7px;border-radius:4px;border:1px solid var(--amber-bd);color:var(--amber);white-space:nowrap}
        .c-date{font-family:'JetBrains Mono',monospace;font-size:11.5px;color:var(--text-sub)}
        .c-name{font-weight:700}
        .c-ph{font-family:'JetBrains Mono',monospace;font-size:12px}
        .c-loc{font-size:12px;color:var(--text-sub)}
        .c-total{font-family:'JetBrains Mono',monospace;font-size:13px;font-weight:700;color:var(--emerald);display:block;text-align:right}
        .call-number{font-family:monospace;font-weight:700;color:var(--primary)}

        /* ── Badges ── */
        .dc-badge{font-size:10.5px;font-weight:700;letter-spacing:.5px;text-transform:uppercase;padding:3px 9px;border-radius:20px;display:inline-flex;align-items:center;gap:5px;border:1px solid;white-space:nowrap}
        .dc-badge::before{content:'';width:5px;height:5px;border-radius:50%;background:currentColor;opacity:.7}
        .badge-pending,.dc-badge-pending{background:var(--amber-lt);border-color:var(--amber-bd);color:var(--amber)}
        .badge-packing{background:var(--primary-lt);border-color:rgba(59,130,246,.25);color:var(--primary)}
        .badge-packed{background:var(--violet-lt);border-color:var(--violet-bd);color:var(--violet)}
        .badge-give-driver,.badge-givedriver{background:var(--emerald-lt);border-color:var(--emerald-bd);color:var(--emerald)}
        .waybill-badge{font-family:'JetBrains Mono',monospace;font-size:11px;background:var(--sky-lt);color:var(--sky);padding:3px 8px;border-radius:5px;border:1px solid var(--sky-bd)}
        .no-city-warn{font-size:11px;background:var(--amber-lt);color:#92400e;padding:2px 7px;border-radius:4px;border:1px solid var(--amber-bd)}
        .curfox-error{font-size:11px;color:var(--rose);background:var(--rose-lt);padding:3px 8px;border-radius:5px;border:1px solid var(--rose-bd);margin-top:4px;display:block}
        tbody tr.row-ok td{background:var(--emerald-lt)!important}
        tbody tr.row-fail td{background:var(--rose-lt)!important}

        /* ── Origin badge & edit ── */
        .origin-wrap{display:flex;align-items:center;gap:4px}
        .origin-badge{display:inline-flex;align-items:center;gap:4px;font-family:'JetBrains Mono',monospace;font-size:11px;font-weight:600;padding:3px 8px;border-radius:5px;background:var(--sky-lt);color:var(--sky);border:1px solid var(--sky-bd);white-space:nowrap;line-height:1.3}
        .origin-badge.is-default{background:var(--amber-lt);color:var(--amber);border-color:var(--amber-bd)}
        .origin-badge .def-tag{font-size:9px;opacity:.75;font-weight:500}
        .origin-badge.custom{background:var(--primary-lt);color:var(--primary-dark);border-color:rgba(59,130,246,.3)}
        .origin-edit-btn{background:none;border:1px solid transparent;cursor:pointer;padding:3px 5px;border-radius:5px;color:var(--text-muted);line-height:1;transition:all .14s;font-size:13px;flex-shrink:0}
        .origin-edit-btn:hover{background:var(--emerald-lt);border-color:var(--emerald-bd);color:var(--emerald)}

        /* ── City autocomplete ── */
        .city-ac-wrap{position:relative}
        .city-ac-input{width:100%;border:1px solid var(--border);border-radius:8px;padding:9px 12px;font-family:'Plus Jakarta Sans',sans-serif;font-size:13px;color:var(--text);outline:none;background:var(--surface);transition:border-color .15s}
        .city-ac-input:focus{border-color:var(--primary);box-shadow:0 0 0 3px rgba(59,130,246,.1)}
        .city-ac-drop{display:none;position:absolute;top:calc(100% + 4px);left:0;right:0;background:var(--surface);border:1px solid var(--border);border-radius:8px;box-shadow:var(--sh);z-index:200;max-height:210px;overflow-y:auto}
        .city-ac-drop.open{display:block}
        .city-ac-item{display:flex;align-items:center;justify-content:space-between;padding:9px 12px;cursor:pointer;transition:background .1s}
        .city-ac-item:hover{background:var(--primary-lt)}
        .city-ac-name{font-size:13px;font-weight:600;color:var(--text)}
        .city-ac-state{font-size:11px;color:var(--text-muted);font-family:'JetBrains Mono',monospace}
        .city-ac-empty{padding:10px 12px;font-size:13px;color:var(--text-muted);pointer-events:none}

        /* ── Origin modal ── */
        .om-overlay{display:none;position:fixed;inset:0;background:rgba(15,23,42,.55);z-index:1000;align-items:center;justify-content:center;padding:20px;backdrop-filter:blur(2px)}
        .om-overlay.open{display:flex}
        .om-box{background:var(--surface);border-radius:var(--r-xl);box-shadow:0 24px 64px rgba(15,23,42,.2);width:100%;max-width:460px;overflow:hidden;animation:omUp .2s cubic-bezier(.16,1,.3,1)}
        @keyframes omUp{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)}}
        .om-head{display:flex;align-items:center;justify-content:space-between;padding:18px 22px;border-bottom:1px solid var(--border);background:var(--primary);color:#fff}
        .om-head h3{font-size:15px;font-weight:800;display:flex;align-items:center;gap:8px}
        .om-close{background:none;border:none;cursor:pointer;font-size:19px;color:#fff;line-height:1;padding:2px 5px;border-radius:5px;transition:all .14s}
        .om-close:hover{background:rgba(255,255,255,.2)}
        .om-body{padding:22px}
        .om-foot{display:flex;gap:10px;justify-content:flex-end;padding:14px 22px;border-top:1px solid var(--border);background:var(--surface-2)}
        .om-note{font-size:12.5px;color:var(--text-sub);line-height:1.6;padding:10px 12px;background:var(--primary-lt);border:1px solid rgba(59,130,246,.25);border-radius:7px;margin-bottom:18px}
        .om-field{margin-bottom:16px}
        .om-field label{display:block;font-size:11px;font-weight:700;color:var(--text-sub);text-transform:uppercase;letter-spacing:.7px;margin-bottom:6px}
        .om-field .readonly-val{padding:9px 12px;border:1px solid var(--border);border-radius:8px;background:var(--surface-2);font-size:13px;color:var(--text-sub);font-family:'JetBrains Mono',monospace;min-height:38px}

        /* ── Business modal ── */
        .bm-overlay{display:none;position:fixed;inset:0;background:rgba(15,23,42,.55);z-index:1000;align-items:center;justify-content:center;padding:20px;backdrop-filter:blur(2px)}
        .bm-overlay.open{display:flex}
        .bm-box{background:var(--surface);border-radius:var(--r-xl);box-shadow:0 24px 64px rgba(15,23,42,.2);width:100%;max-width:480px;overflow:hidden;animation:omUp .2s cubic-bezier(.16,1,.3,1)}
        .bm-head{display:flex;align-items:center;justify-content:space-between;padding:18px 22px;border-bottom:1px solid var(--border);background:linear-gradient(135deg,var(--violet-lt) 0%,var(--surface-2) 100%)}
        .bm-head h3{font-size:15px;font-weight:800;color:var(--text);display:flex;align-items:center;gap:8px}
        .bm-close{background:none;border:none;cursor:pointer;font-size:19px;color:var(--text-muted);line-height:1;padding:2px 5px;border-radius:5px;transition:all .14s}
        .bm-close:hover{background:var(--rose-lt);color:var(--rose)}
        .bm-body{padding:22px}
        .bm-foot{display:flex;gap:10px;justify-content:flex-end;padding:14px 22px;border-top:1px solid var(--border);background:var(--surface-2)}
        .bm-note{font-size:12.5px;color:var(--text-sub);line-height:1.6;padding:10px 12px;background:var(--violet-lt);border:1px solid var(--violet-bd);border-radius:7px;margin-bottom:18px}
        .biz-card{border:2px solid var(--border);border-radius:10px;padding:14px 16px;cursor:pointer;transition:all .16s;margin-bottom:10px;display:flex;align-items:center;gap:12px;position:relative}
        .biz-card:hover{border-color:var(--violet);background:var(--violet-lt)}
        .biz-card.selected{border-color:var(--violet);background:var(--violet-lt);box-shadow:0 0 0 3px var(--violet-bd)}
        .biz-card-ico{width:40px;height:40px;border-radius:9px;background:var(--violet-lt);border:1px solid var(--violet-bd);display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0}
        .biz-card.selected .biz-card-ico{background:var(--violet);border-color:var(--violet);color:#fff}
        .biz-card-name{font-size:14px;font-weight:700;color:var(--text)}
        .biz-card-meta{font-family:'JetBrains Mono',monospace;font-size:11px;color:var(--text-muted);margin-top:2px}
        .biz-card-check{width:20px;height:20px;border-radius:50%;background:var(--violet);border:none;display:flex;align-items:center;justify-content:center;font-size:11px;color:#fff;margin-left:auto;flex-shrink:0;display:none}
        .biz-card.selected .biz-card-check{display:flex}
        .biz-card-default{font-size:10px;font-weight:700;background:var(--emerald-lt);color:var(--emerald);border:1px solid var(--emerald-bd);padding:1px 6px;border-radius:10px;margin-left:6px;vertical-align:middle}

        /* ── Empty state ── */
        .dc-empty{text-align:center;padding:56px 0}
        .dc-empty-ico{width:52px;height:52px;background:var(--surface-3);border-radius:13px;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;font-size:21px;border:1px solid var(--border)}

        /* ── Pagination ── */
        nav[aria-label] span[aria-current=page]>span,nav[aria-label] a,nav[aria-label] button{background:var(--surface)!important;border:1px solid var(--border)!important;color:var(--text-sub)!important;border-radius:7px!important;font-family:'JetBrains Mono',monospace!important;font-size:12px!important;padding:5px 12px!important;display:inline-flex!important;align-items:center!important;justify-content:center!important;min-width:34px;height:32px;text-decoration:none;line-height:1!important;transition:all .14s}
        nav[aria-label] a:hover{background:var(--primary)!important;border-color:var(--primary)!important;color:#fff!important}
        nav[aria-label] span[aria-current=page]>span{background:var(--primary)!important;border-color:var(--primary)!important;color:#fff!important}
        nav[aria-label]>div:first-child{display:none}
        nav[aria-label] .flex{gap:4px;flex-wrap:wrap;align-items:center}

        /* ── Print modal ── */
        .print-modal{display:none;position:fixed;inset:0;background:rgba(15,23,42,.6);z-index:2000;align-items:center;justify-content:center;padding:20px;backdrop-filter:blur(3px)}
        .print-modal.open{display:flex}
        .print-modal-content{background:var(--surface);border-radius:16px;box-shadow:0 24px 64px rgba(0,0,0,.22);width:100%;max-width:540px;overflow:hidden;animation:plUp .22s cubic-bezier(.16,1,.3,1)}
        @keyframes plUp{from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:translateY(0)}}
        .print-modal-header{display:flex;align-items:center;justify-content:space-between;padding:16px 22px;border-bottom:1px solid var(--border);background:linear-gradient(135deg,var(--primary-lt),var(--surface-2))}
        .print-modal-body{padding:20px}
        .print-modal-footer{display:flex;gap:10px;justify-content:flex-end;padding:14px 22px;border-top:1px solid var(--border);background:var(--surface-2)}
        .label-preview{border:2px solid #000;font-family:Arial,sans-serif;font-size:12px;color:#000;border-radius:2px}
        .label-preview-header{display:flex;align-items:center;justify-content:space-between;padding:8px 12px;border-bottom:2px solid #000;background:#f8f8f8}
        .waybill-barcode{text-align:center;padding:6px;border-bottom:2px solid #000;font-family:monospace;font-size:18px;letter-spacing:3px;background:#fff}
        .customer-section{display:flex;border-bottom:2px solid #000}
        .customer-details{flex:1;padding:10px 12px}
        .order-summary{width:160px;border-left:2px solid #000;padding:10px 12px;display:flex;flex-direction:column}
        .item-row{display:flex;border-bottom:2px solid #000}
        .item-cell{padding:7px 12px;border-right:2px solid #000}
        .item-cell:last-child{border-right:none}
        .remark-section{padding:7px 12px;background:var(--amber-lt);border-bottom:2px solid #000}
        .footer-note{padding:5px 12px;background:#f8f8f8;font-size:10px;color:#666;text-align:center}

        @media(max-width:1200px){.stats-grid{grid-template-columns:repeat(2,1fr)}}
        @media(max-width:768px){.dc-page{padding:16px}.dc-chead{flex-direction:column;align-items:stretch}.dc-sf{width:100%}.dc-si{width:100%}}
    </style>

    <div class="dc-page">
        <div class="dc-wrap">

            {{-- Header with status tabs --}}
            <div class="dc-hdr">
                <div>
                    <div class="dc-crumb">Seller Portal › Delivery Calls › <span style="color:var(--primary);font-weight:700">{{ ucfirst($status) }}</span></div>
                    <div class="dc-title">
                        Delivery Calls <span class="dc-tbadge">{{ ucfirst($status) }}</span>
                        @if($status === 'packed')
                            <span style="font-size:12px;font-weight:600;color:var(--sky);background:var(--sky-lt);padding:3px 10px;border-radius:20px;border:1px solid var(--sky-bd)">🚚 Next: Dispatch to Courier</span>
                        @endif
                    </div>
                    <div class="dc-sub">
                        Showing <strong>{{ ucfirst($status) }}</strong> records
                        @if($nextStatus)
                            → Next: <span style="font-weight:700;color:var(--emerald)">{{ ucfirst($nextStatus) }}</span>
                            @if($nextStatus === 'givedriver')<span style="color:var(--sky);font-weight:700">(Curfox API dispatch)</span>@endif
                        @else
                            <span style="color:var(--emerald);font-weight:700">✓ Final Stage</span>
                        @endif
                    </div>
                </div>
                <div class="dc-tabs">
                    <a class="dc-tab {{ request()->routeIs('seller.delivery_calls.pending') ? 'active' : '' }}" href="{{ route('seller.delivery_calls.pending') }}">Pending</a>
                    <a class="dc-tab {{ request()->routeIs('seller.delivery_calls.packing') ? 'active' : '' }}" href="{{ route('seller.delivery_calls.packing') }}">Packing</a>
                    <a class="dc-tab {{ request()->routeIs('seller.delivery_calls.packed') ? 'active' : '' }}" href="{{ route('seller.delivery_calls.packed') }}">Packed</a>
                    <a class="dc-tab {{ request()->routeIs('seller.delivery_calls.givedriver') ? 'active' : '' }}" href="{{ route('seller.delivery_calls.givedriver') }}">Give Driver</a>
                </div>
            </div>

            {{-- Alerts --}}
            @if(session('success'))<div class="dc-alert dc-alert-ok">✓ &nbsp;{{ session('success') }}</div>@endif
            @if(session('error'))<div class="dc-alert dc-alert-err">✕ &nbsp;{{ session('error') }}</div>@endif

            {{-- Business Banner --}}
            <div class="biz-banner">
                <div class="biz-banner-l">
                    <div class="biz-ico">🏢</div>
                    <div>
                        <div class="biz-label">Active Curfox Business</div>
                        @if(!empty($curfoxBusinesses))
                            @php
                                $activeBiz = collect($curfoxBusinesses)->firstWhere('id', $selectedBusinessId)
                                          ?? collect($curfoxBusinesses)->first();
                            @endphp
                            <div style="display:flex;align-items:center;gap:4px">
                                <span class="biz-name" id="activeBizName">{{ $activeBiz['business_name'] ?? '—' }}</span>
                                <span class="biz-id" id="activeBizId">ID: {{ $selectedBusinessId }}</span>
                            </div>
                        @else
                            <div class="biz-no-data">No businesses loaded — using default ID {{ $selectedBusinessId }}</div>
                        @endif
                    </div>
                </div>
                <div>
                    @if(!empty($curfoxBusinesses))
                        <button type="button" class="btn-dc btn-violet" onclick="openBizModal()">
                            ⚙ Switch Business
                        </button>
                    @else
                        <form action="{{ route('seller.delivery_calls.set_business') }}" method="POST" class="biz-form">
                            @csrf
                            <input type="text" name="merchant_business_id"
                                   value="{{ $selectedBusinessId }}"
                                   class="biz-select" style="min-width:120px"
                                   placeholder="Business ID">
                            <button type="submit" class="btn-dc btn-violet">Set</button>
                        </form>
                    @endif
                </div>
            </div>

            {{-- Stats Grid --}}
            <div class="stats-grid">
                <div class="stat-card pending"    onclick="window.location='{{ route('seller.delivery_calls.pending') }}'"><div class="stat-value">{{ $counts['pending'] ?? 0 }}</div><div class="stat-label">Pending</div></div>
                <div class="stat-card packing"    onclick="window.location='{{ route('seller.delivery_calls.packing') }}'"><div class="stat-value">{{ $counts['packing'] ?? 0 }}</div><div class="stat-label">Packing</div></div>
                <div class="stat-card packed"     onclick="window.location='{{ route('seller.delivery_calls.packed') }}'"><div class="stat-value">{{ $counts['packed'] ?? 0 }}</div><div class="stat-label">Packed</div></div>
                <div class="stat-card givedriver" onclick="window.location='{{ route('seller.delivery_calls.givedriver') }}'"><div class="stat-value">{{ $counts['givedriver'] ?? 0 }}</div><div class="stat-label">Give Driver</div></div>
            </div>

            @php $pageSubtotal = $calls->sum(fn($c) => ($c->price ?? 0) * ($c->qty ?? 1)); @endphp

            {{-- Main Card --}}
            <div class="dc-card">
                <div class="dc-chead">
                    <form class="dc-sf" method="GET" action="{{ url()->current() }}">
                        <div class="dc-sw">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                            <input type="text" name="q" class="dc-si" value="{{ request('q') }}" placeholder="Search order code, customer, phone…">
                        </div>
                        <button class="btn-dc btn-em" type="submit">Search</button>
                        <a class="btn-dc btn-ghost" href="{{ url()->current() }}">Reset</a>
                    </form>
                    <div style="font-size:12px;color:var(--text-muted);font-weight:500">Page {{ $calls->currentPage() }} / {{ $calls->lastPage() }}</div>
                </div>

                <div class="dc-cbody">

                    {{-- Bulk Actions --}}
                    <div class="bulk-actions">
                        <div class="bulk-actions-left">
                            <div class="form-check">
                                <input type="checkbox" class="chk-main" id="selectAll">
                                <label class="form-check-label fw-semibold ms-2">Select All</label>
                            </div>
                            <span class="selected-count" id="selectedCount">0 selected</span>
                        </div>
                        <div class="d-flex gap-2">
                            @if($nextStatus)
                                <button class="btn-dc btn-primary" id="bulkPrintBtn" disabled>
                                    🖨 Print Selected Labels
                                </button>
                                <button type="button" id="bulkUpdateBtn" class="btn-dc {{ $nextStatus === 'givedriver' ? 'btn-dispatch' : 'btn-bulk' }}">
                                    @if($nextStatus === 'givedriver') 🚚 Bulk Dispatch to Curfox
                                    @else Bulk Update → {{ ucfirst($nextStatus) }}
                                    @endif
                                </button>
                                <span id="bulkProgress" style="display:none;font-size:12px;font-weight:600;color:var(--emerald);background:#fff;padding:4px 12px;border-radius:20px;border:1px solid var(--emerald-bd);font-family:'JetBrains Mono',monospace"></span>
                            @else
                                <span class="btn-sm-final">Final Status</span>
                            @endif
                        </div>
                    </div>

                    {{-- Bulk Origin Bar --}}
                    <div class="bulk-origin-bar" id="bulkOriginBar">
                        <span class="fw-bold" style="color:var(--amber)">
                            📍 Set Pickup Origin:
                        </span>
                        <div class="origin-input-group">
                            <input type="text" id="bulkOriginInput" placeholder="Type city name..." autocomplete="off">
                            <div class="city-dropdown" id="bulkOriginDropdown"></div>
                        </div>
                        <button class="btn-dc btn-warning" id="bulkOriginApplyBtn" disabled>Apply to Selected</button>
                    </div>

                    {{-- Table --}}
                    <div class="dc-twrap">
                        <table class="dc-t">
                            <thead>
                            <tr>
                                <th style="width:44px;text-align:center"></th>
                                <th>Call #</th>
                                <th>Order Code</th>
                                <th>Customer</th>
                                <th>Phone</th>
                                <th>Destination</th>
                                <th>Pickup Origin</th>
                                <th>Item</th>
                                <th>Qty</th>
                                <th>Total</th>
                                <th>Status</th>
                                @if($status === 'givedriver')<th>Waybill</th>@endif
                                <th style="width:180px">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($calls as $c)
                                @php
                                    $lineTotal   = ($c->price ?? 0) * ($c->qty ?? 1);
                                    $hasCustom   = $c->hasCustomOrigin();
                                    $originName  = $c->effectiveOriginCityName();
                                    $originState = $c->effectiveOriginStateName();
                                    $originId    = $c->effectiveOriginCityId();

                                    // Build label data for printing
                                    $labelData = [
                                        'order_code' => $c->order_code ?? $c->call_number,
                                        'call_number' => $c->call_number,
                                        'customer_name' => $c->customer_name,
                                        'customer_phone_01' => $c->customer_phone_01,
                                        'customer_phone_02' => $c->customer_phone_02,
                                        'customer_address' => $c->customer_address,
                                        'city' => $c->city ?? $c->district,
                                        'destination_state_name' => $c->destination_state_name,
                                        'item_code' => $c->item_code,
                                        'item_name' => $c->item_name,
                                        'qty' => $c->qty,
                                        'price' => $c->price,
                                        'remark' => $c->remark,
                                        'delivery_remark' => $c->delivery_remark,
                                        'curfox_waybill' => $c->curfox_waybill,
                                    ];
                                @endphp
                                <tr id="row_tr_{{ $c->id }}" class="{{ $c->curfox_waybill ? 'row-ok' : '' }}">
                                    <td style="text-align:center">
                                        <input type="checkbox" class="rowCheck" value="{{ $c->id }}">
                                    </td>
                                    <td><span class="call-number">{{ $c->call_number }}</span></td>
                                    <td>@if($c->order_code)<span class="c-oc">{{ $c->order_code }}</span>@else<span style="color:var(--text-muted)">—</span>@endif</td>
                                    <td>
                                        <div class="c-name">{{ $c->customer_name }}</div>
                                        <div style="font-size:11px;color:var(--text-muted)">{{ Str::limit($c->customer_address, 30) }}</div>
                                    </td>
                                    <td>
                                        <div class="c-ph">{{ $c->customer_phone_01 }}</div>
                                        @if($c->customer_phone_02)<div style="font-family:monospace;font-size:10.5px;color:var(--text-muted)">{{ $c->customer_phone_02 }}</div>@endif
                                    </td>

                                    {{-- Destination --}}
                                    <td>
                                        @if($c->city || $c->district)
                                            <span class="c-loc">{{ $c->city ?? $c->district }}</span>
                                            @if($c->destination_city_id)
                                                <span style="font-size:10px;color:var(--emerald);background:var(--emerald-lt);padding:1px 5px;border-radius:3px;margin-left:4px">✓ API</span>
                                            @else
                                                <span class="no-city-warn">⚠ No city ID</span>
                                            @endif
                                        @else
                                            <span class="no-city-warn">No city set</span>
                                        @endif
                                        @if($c->curfox_error)
                                            <span class="curfox-error" title="{{ $c->curfox_error }}">✕ {{ Str::limit($c->curfox_error, 55) }}</span>
                                        @endif
                                    </td>

                                    {{-- Origin (editable) --}}
                                    <td>
                                        <div class="origin-wrap" id="origin_wrap_{{ $c->id }}">
                                            <span class="origin-badge {{ $hasCustom ? 'custom' : 'is-default' }}"
                                                  id="origin_badge_{{ $c->id }}">
                                                {{ $originName }}
                                                @if(! $hasCustom)<span class="def-tag">(default)</span>@endif
                                            </span>
                                            <button type="button"
                                                    class="origin-edit-btn"
                                                    title="Edit pickup origin city"
                                                    data-id="{{ $c->id }}"
                                                    data-city-id="{{ $originId ?? '' }}"
                                                    data-city-name="{{ $originName }}"
                                                    data-state-name="{{ $originState }}">
                                                ✎
                                            </button>
                                        </div>
                                    </td>

                                    <td>
                                        <span style="font-size:13px;font-weight:600">{{ Str::limit($c->item_name, 20) }}</span>
                                        @if($c->item_code)<div><small class="text-muted">{{ $c->item_code }}</small></div>@endif
                                    </td>
                                    <td style="text-align:center;font-family:monospace;font-weight:700;font-size:14px">{{ $c->qty }}</td>
                                    <td><span class="c-total">Rs. {{ number_format($lineTotal, 2) }}</span></td>
                                    <td>
                                        <span class="dc-badge badge-{{ strtolower(str_replace(' ','-',$c->status)) }}" id="badge_{{ $c->id }}">
                                            {{ ucfirst($c->status) }}
                                        </span>
                                    </td>
                                    @if($status === 'givedriver')
                                        <td>
                                            @if($c->curfox_waybill)
                                                <span class="waybill-badge">{{ $c->curfox_waybill }}</span>
                                            @else
                                                <span style="color:var(--text-muted);font-size:12px">Pending</span>
                                            @endif
                                        </td>
                                    @endif
                                    <td>
                                        <div style="display:flex;gap:6px;align-items:center;flex-wrap:wrap">
                                            <a class="action-btn view" href="{{ route('seller.delivery_calls.show', $c->id) }}" title="View">
                                                <span class="material-icons-round" style="font-size:18px">visibility</span>
                                            </a>

                                            <button type="button"
                                                    class="action-btn print print-label-btn"
                                                    title="Print Packing Label"
                                                    data-call='@json($labelData)'>
                                                <span class="material-icons-round" style="font-size:18px">print</span>
                                            </button>

                                            @if($nextStatus)
                                                @if($nextStatus === 'givedriver')
                                                    <button class="action-btn next dispatch-single-btn"
                                                            data-id="{{ $c->id }}"
                                                            data-name="{{ $c->customer_name }}"
                                                            data-city="{{ $c->city ?? $c->district ?? 'Unknown' }}"
                                                            {{ $c->curfox_waybill ? 'disabled' : '' }}
                                                            type="button"
                                                            title="Dispatch to Courier">
                                                        <span class="material-icons-round" style="font-size:18px">local_shipping</span>
                                                    </button>
                                                @else
                                                    <form method="POST"
                                                          action="{{ route('seller.delivery_calls.update_status', $c->id) }}"
                                                          onsubmit="return confirm('Update to {{ ucfirst($nextStatus) }}?')"
                                                          style="display:contents">
                                                        @csrf
                                                        <button class="action-btn next" type="submit" title="Move to {{ ucfirst($nextStatus) }}">
                                                            <span class="material-icons-round" style="font-size:18px">arrow_forward</span>
                                                        </button>
                                                    </form>
                                                @endif
                                            @else
                                                <span class="btn-sm-final">Final</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $status === 'givedriver' ? 13 : 12 }}">
                                        <div class="dc-empty">
                                            <div class="dc-empty-ico">📭</div>
                                            <div style="font-size:15px;font-weight:700;color:var(--text);margin-bottom:5px">No records found</div>
                                            <div style="font-size:13px;color:var(--text-muted)">No {{ ucfirst($status) }} delivery calls match your search.</div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                            @if($calls->count())
                                <tfoot>
                                <tr>
                                    <td colspan="{{ $status === 'givedriver' ? 9 : 8 }}" style="text-align:right;padding:11px 13px">
                                        <span style="font-size:11px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.7px">Page Subtotal</span>
                                    </td>
                                    <td style="text-align:right;padding:11px 13px">
                                        <span style="font-size:14px;font-weight:800;color:var(--emerald)">Rs. {{ number_format($pageSubtotal, 2) }}</span>
                                    </td>
                                    <td colspan="{{ $status === 'givedriver' ? 3 : 2 }}"></td>
                                </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div style="margin-top:18px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px">
                        <div style="font-size:12.5px;color:var(--text-muted);font-weight:500">
                            @if($calls->total()) Showing {{ $calls->firstItem() }}–{{ $calls->lastItem() }} of {{ $calls->total() }} results @endif
                        </div>
                        {{ $calls->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Origin Edit Modal --}}
    <div class="om-overlay" id="originModal">
        <div class="om-box">
            <div class="om-head">
                <h3>📍 Edit Pickup Origin</h3>
                <button class="om-close" onclick="closeOriginModal()">✕</button>
            </div>
            <div class="om-body">
                <div class="om-note">
                    This is where the parcel is <strong>collected from</strong>. It must match your Curfox rate card
                    pickup location (e.g. <em>Kotte</em>, <em>Veyangoda</em>, <em>Colombo 01</em>).
                </div>
                <div class="om-field">
                    <label>Search &amp; Select City</label>
                    <div class="city-ac-wrap">
                        <input type="text" id="omSearch" class="city-ac-input" placeholder="Type to search Curfox cities…" autocomplete="off">
                        <div class="city-ac-drop" id="omDrop"></div>
                    </div>
                </div>
                <div class="om-field">
                    <label>Selected City</label>
                    <div class="readonly-val" id="omSelectedDisplay">—</div>
                </div>
                <input type="hidden" id="omCallId">
                <input type="hidden" id="omCityId">
                <input type="hidden" id="omCityName">
                <input type="hidden" id="omStateName">
            </div>
            <div class="om-foot">
                <button class="btn-dc btn-ghost" onclick="closeOriginModal()">Cancel</button>
                <button class="btn-dc btn-em" id="omSaveBtn" onclick="saveOriginModal()" disabled>Save Origin</button>
            </div>
        </div>
    </div>

    {{-- Business Select Modal --}}
    <div class="bm-overlay" id="bizModal">
        <div class="bm-box">
            <div class="bm-head">
                <h3>🏢 Select Active Business</h3>
                <button class="bm-close" onclick="closeBizModal()">✕</button>
            </div>
            <div class="bm-body">
                <div class="bm-note">
                    The selected business determines which <strong>Curfox merchant account</strong> is used when
                    dispatching orders to the courier.
                </div>
                <div id="bizCardList">
                    @forelse($curfoxBusinesses as $biz)
                        <div class="biz-card {{ (string)$selectedBusinessId == (string)$biz['id'] ? 'selected' : '' }}"
                             data-biz-id="{{ $biz['id'] }}"
                             data-biz-name="{{ $biz['business_name'] }}"
                             onclick="selectBizCard(this)">
                            <div class="biz-card-ico">{{ (string)$selectedBusinessId == (string)$biz['id'] ? '✓' : '🏬' }}</div>
                            <div style="flex:1;min-width:0">
                                <div class="biz-card-name">
                                    {{ $biz['business_name'] }}
                                    @if($biz['is_default'])
                                        <span class="biz-card-default">Default</span>
                                    @endif
                                </div>
                                <div class="biz-card-meta">
                                    ID: {{ $biz['id'] }} &nbsp;·&nbsp; Ref: {{ $biz['ref_no'] ?? '—' }}
                                </div>
                            </div>
                            <div class="biz-card-check">✓</div>
                        </div>
                    @empty
                        <div style="text-align:center;padding:30px;color:var(--text-muted);font-size:13px">No businesses found</div>
                    @endforelse
                </div>
                <input type="hidden" id="bmSelectedId" value="{{ $selectedBusinessId }}">
                <input type="hidden" id="bmSelectedName" value="">
            </div>
            <div class="bm-foot">
                <button class="btn-dc btn-ghost" onclick="closeBizModal()">Cancel</button>
                <button class="btn-dc btn-violet" id="bmSaveBtn" onclick="saveBizModal()">Apply Business</button>
            </div>
        </div>
    </div>

    {{-- Print Label Modal --}}
    <div class="print-modal" id="printLabelModal">
        <div class="print-modal-content">
            <div class="print-modal-header">
                <div style="font-size:16px;font-weight:800;color:var(--text);display:flex;align-items:center;gap:8px">
                    🖨 Packing Label Preview
                </div>
                <button onclick="closePrintModal()" style="background:none;border:none;cursor:pointer;font-size:18px;color:var(--text-muted);padding:3px 6px;border-radius:6px;line-height:1">✕</button>
            </div>
            <div class="print-modal-body" id="labelPreviewArea">
                <div class="label-preview" id="packingLabelPreview"></div>
            </div>
            <div class="print-modal-footer">
                <button class="btn-dc btn-ghost" onclick="closePrintModal()">Cancel</button>
                <button class="btn-dc btn-em" onclick="doPrintLabel()">🖨 Print Label</button>
                <button class="btn-dc btn-warning" id="printAllBtn" style="display:none" onclick="doPrintAllSelected()">🖨 Print All Selected</button>
            </div>
        </div>
    </div>

    {{-- Hidden form for business POST --}}
    <form id="bizSetForm" action="{{ route('seller.delivery_calls.set_business') }}" method="POST" style="display:none">
        @csrf
        <input type="hidden" name="merchant_business_id" id="bizFormInput">
    </form>

    <script>
        // ── Constants ─────────────────────────────────────────────────────────
        const CURFOX_CITIES = @json($curfoxCities ?? []);
        const CSRF           = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const BASE_URL       = '{{ url("/seller/delivery-calls") }}';
        const BULK_ORIG_URL  = '{{ route("seller.delivery_calls.bulk_origin") }}';
        const nextStatus     = @json($nextStatus);

        // ── City autocomplete helper ──────────────────────────────────────────
        function filterCities(term) {
            if (!term || term.length < 1) return [];
            const t = term.toLowerCase();
            return CURFOX_CITIES.filter(c =>
                c.name.toLowerCase().includes(t) || (c.state_name && c.state_name.toLowerCase().includes(t))
            ).slice(0, 40);
        }

        function renderDrop(results, dropEl, onPick) {
            dropEl.innerHTML = '';
            if (!results.length) {
                const d = document.createElement('div');
                d.className = 'city-ac-empty';
                d.textContent = 'No cities found';
                dropEl.appendChild(d);
            } else {
                results.forEach(c => {
                    const d = document.createElement('div');
                    d.className = 'city-ac-item';
                    d.innerHTML = `<span class="city-ac-name">${c.name}</span><span class="city-ac-state">${c.state_name || ''}</span>`;
                    d.addEventListener('mousedown', e => { e.preventDefault(); onPick(c); });
                    dropEl.appendChild(d);
                });
            }
            dropEl.classList.add('open');
        }

        document.addEventListener('click', () => {
            document.querySelectorAll('.city-ac-drop, .city-dropdown').forEach(d => d.classList.remove('open'));
        });

        // ── Toast ─────────────────────────────────────────────────────────────
        function toast(msg, ok = true) {
            const t = document.createElement('div');
            t.style.cssText = `position:fixed;bottom:22px;right:22px;z-index:2000;padding:11px 18px;border-radius:10px;
                font-family:'Plus Jakarta Sans',sans-serif;font-size:13px;font-weight:600;
                box-shadow:0 8px 24px rgba(0,0,0,.14);animation:omUp .25s ease;` +
                (ok ? 'background:#ecfdf5;color:#059669;border:1px solid rgba(5,150,105,.25)'
                    : 'background:#fff1f2;color:#e11d48;border:1px solid rgba(225,29,72,.25)');
            t.textContent = msg;
            document.body.appendChild(t);
            setTimeout(() => t.remove(), 3000);
        }

        // ── Update origin badge in table row ──────────────────────────────────
        function applyOriginToRow(callId, cityId, cityName, stateName) {
            const badge = document.getElementById('origin_badge_' + callId);
            const btn   = document.querySelector(`.origin-edit-btn[data-id="${callId}"]`);
            if (badge) {
                badge.innerHTML = cityName + (stateName ? ` <small>(${stateName})</small>` : '');
                badge.classList.remove('is-default', 'custom');
                badge.classList.add('custom');
            }
            if (btn) {
                btn.dataset.cityId    = cityId;
                btn.dataset.cityName  = cityName;
                btn.dataset.stateName = stateName;
            }
        }

        // ══ BUSINESS MODAL ════════════════════════════════════════════════════

        function openBizModal() {
            document.getElementById('bizModal').classList.add('open');
        }

        function closeBizModal() {
            document.getElementById('bizModal').classList.remove('open');
        }

        document.getElementById('bizModal').addEventListener('click', function(e) {
            if (e.target === this) closeBizModal();
        });

        function selectBizCard(el) {
            document.querySelectorAll('.biz-card').forEach(c => {
                c.classList.remove('selected');
                c.querySelector('.biz-card-ico').textContent = '🏬';
            });
            el.classList.add('selected');
            el.querySelector('.biz-card-ico').textContent = '✓';
            document.getElementById('bmSelectedId').value   = el.dataset.bizId;
            document.getElementById('bmSelectedName').value = el.dataset.bizName;
        }

        function saveBizModal() {
            const id   = document.getElementById('bmSelectedId').value;
            const name = document.getElementById('bmSelectedName').value || 'ID ' + id;
            if (!id) { alert('Please select a business.'); return; }

            const btn = document.getElementById('bmSaveBtn');
            btn.disabled = true;
            btn.textContent = 'Saving…';

            document.getElementById('bizFormInput').value = id;
            document.getElementById('bizSetForm').submit();

            const nameEl = document.getElementById('activeBizName');
            const idEl   = document.getElementById('activeBizId');
            if (nameEl) nameEl.textContent = name;
            if (idEl)   idEl.textContent   = 'ID: ' + id;
        }

        // ══ ORIGIN MODAL ══════════════════════════════════════════════════════

        let omSelected = null;

        function openOriginModal(callId, currentCityId, currentCityName, currentStateName) {
            document.getElementById('omCallId').value    = callId;
            document.getElementById('omSearch').value    = currentCityName || '';
            document.getElementById('omCityId').value    = currentCityId   || '';
            document.getElementById('omCityName').value  = currentCityName || '';
            document.getElementById('omStateName').value = currentStateName|| '';
            document.getElementById('omSelectedDisplay').textContent =
                currentCityName ? `${currentCityName} — ${currentStateName}` : '—';
            document.getElementById('omSaveBtn').disabled = !currentCityName;
            document.getElementById('omDrop').classList.remove('open');
            omSelected = currentCityId ? { id: currentCityId, name: currentCityName, state_name: currentStateName } : null;
            document.getElementById('originModal').classList.add('open');
            setTimeout(() => document.getElementById('omSearch').focus(), 120);
        }

        function closeOriginModal() {
            document.getElementById('originModal').classList.remove('open');
            omSelected = null;
        }

        document.getElementById('originModal').addEventListener('click', function(e) {
            if (e.target === this) closeOriginModal();
        });

        document.querySelectorAll('.origin-edit-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                openOriginModal(
                    btn.dataset.id,
                    btn.dataset.cityId    || '',
                    btn.dataset.cityName  || '',
                    btn.dataset.stateName || ''
                );
            });
        });

        document.getElementById('omSearch').addEventListener('input', function() {
            const res = filterCities(this.value);
            if (!this.value.trim()) {
                document.getElementById('omDrop').classList.remove('open');
                return;
            }
            renderDrop(res, document.getElementById('omDrop'), city => {
                omSelected = city;
                document.getElementById('omSearch').value    = city.name;
                document.getElementById('omCityId').value    = city.id;
                document.getElementById('omCityName').value  = city.name;
                document.getElementById('omStateName').value = city.state_name || '';
                document.getElementById('omSelectedDisplay').textContent = `${city.name} — ${city.state_name || ''}`;
                document.getElementById('omSaveBtn').disabled = false;
                document.getElementById('omDrop').classList.remove('open');
            });
        });

        async function saveOriginModal() {
            const callId    = document.getElementById('omCallId').value;
            const cityId    = parseInt(document.getElementById('omCityId').value);
            const cityName  = document.getElementById('omCityName').value;
            const stateName = document.getElementById('omStateName').value;

            if (!cityId || !cityName) { alert('Please select a city.'); return; }

            const btn = document.getElementById('omSaveBtn');
            btn.disabled = true; btn.textContent = 'Saving…';

            try {
                const res  = await fetch(`${BASE_URL}/${callId}/origin`, {
                    method: 'PATCH',
                    headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN':CSRF, 'Accept':'application/json' },
                    body: JSON.stringify({ origin_city_id: cityId, origin_city_name: cityName, origin_state_name: stateName })
                });
                const data = await res.json();
                if (data.success) {
                    applyOriginToRow(callId, cityId, cityName, stateName);
                    closeOriginModal();
                    toast(`✓ Origin → ${cityName} (${stateName})`);
                } else {
                    toast('✕ ' + data.message, false);
                }
            } catch(e) {
                toast('✕ Network error', false);
            } finally {
                btn.disabled = false; btn.textContent = 'Save Origin';
            }
        }

        // ══ BULK ORIGIN BAR ═══════════════════════════════════════════════════

        let bulkCitySelected = null;

        document.getElementById('bulkOriginInput')?.addEventListener('input', function() {
            bulkCitySelected = null;
            document.getElementById('bulkOriginApplyBtn').disabled = true;
            if (!this.value.trim()) { document.getElementById('bulkOriginDropdown').classList.remove('open'); return; }

            const results = filterCities(this.value);
            const drop = document.getElementById('bulkOriginDropdown');
            drop.innerHTML = '';

            if (!results.length) {
                drop.innerHTML = '<div class="city-item">No cities found</div>';
            } else {
                results.forEach(city => {
                    const div = document.createElement('div');
                    div.className = 'city-item';
                    div.innerHTML = `<span class="city-name">${city.name}</span><span class="city-state">${city.state_name || ''}</span>`;
                    div.addEventListener('click', () => {
                        bulkCitySelected = city;
                        document.getElementById('bulkOriginInput').value = city.name;
                        document.getElementById('bulkOriginApplyBtn').disabled = false;
                        drop.classList.remove('open');
                    });
                    drop.appendChild(div);
                });
            }
            drop.classList.add('open');
        });

        document.getElementById('bulkOriginApplyBtn')?.addEventListener('click', async () => {
            if (!bulkCitySelected) return;
            const ids = Array.from(document.querySelectorAll('.rowCheck:checked')).map(c => parseInt(c.value));
            if (!ids.length) { alert('Select at least one row.'); return; }

            const btn = document.getElementById('bulkOriginApplyBtn');
            btn.disabled = true; btn.textContent = 'Saving…';

            try {
                const res  = await fetch(BULK_ORIG_URL, {
                    method: 'PATCH',
                    headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN':CSRF, 'Accept':'application/json' },
                    body: JSON.stringify({
                        ids,
                        origin_city_id:    bulkCitySelected.id,
                        origin_city_name:  bulkCitySelected.name,
                        origin_state_name: bulkCitySelected.state_name || ''
                    })
                });
                const data = await res.json();
                if (data.success) {
                    ids.forEach(id => applyOriginToRow(id, bulkCitySelected.id, bulkCitySelected.name, bulkCitySelected.state_name || ''));
                    toast(`✓ ${data.message}`);
                } else { toast('✕ ' + data.message, false); }
            } catch(e) { toast('✕ Network error', false); }
            finally { btn.disabled = false; btn.textContent = 'Apply to Selected'; }
        });

        // ══ CHECKBOXES ════════════════════════════════════════════════════════

        const selectAll     = document.getElementById('selectAll');
        const bulkOriginBar = document.getElementById('bulkOriginBar');
        const bulkPrintBtn  = document.getElementById('bulkPrintBtn');
        const bulkUpdateBtn = document.getElementById('bulkUpdateBtn');

        function syncCheckboxes() {
            const all = Array.from(document.querySelectorAll('.rowCheck'));
            const chk = all.filter(c => c.checked);
            const selectedCount = document.getElementById('selectedCount');
            if (selectedCount) selectedCount.textContent = chk.length + ' selected';

            if (selectAll) {
                selectAll.indeterminate = chk.length > 0 && chk.length < all.length;
                selectAll.checked       = chk.length > 0 && chk.length === all.length;
            }

            if (bulkOriginBar) bulkOriginBar.classList.toggle('show', chk.length > 0);
            if (bulkPrintBtn) bulkPrintBtn.disabled = chk.length === 0;
            if (bulkUpdateBtn) bulkUpdateBtn.disabled = chk.length === 0;
        }

        if (selectAll) selectAll.addEventListener('change', () => {
            document.querySelectorAll('.rowCheck').forEach(c => c.checked = selectAll.checked);
            syncCheckboxes();
        });

        document.querySelectorAll('.rowCheck').forEach(c => c.addEventListener('change', syncCheckboxes));

        // ══ PACKING LABEL PRINTING ═════════════════════════════════════════════

        let _currentLabelData   = null;
        let _selectedLabelDatas = [];

        function openPrintModal(data, showBulk = false) {
            _currentLabelData = data;
            document.getElementById('packingLabelPreview').innerHTML = buildLabelHtml(data);
            document.getElementById('printLabelModal').classList.add('open');
            const pab = document.getElementById('printAllBtn');
            if (pab) {
                pab.style.display = showBulk && _selectedLabelDatas.length > 1 ? 'inline-flex' : 'none';
                if (showBulk) pab.textContent = `🖨 Print All ${_selectedLabelDatas.length} Labels`;
            }
        }

        function closePrintModal() {
            document.getElementById('printLabelModal').classList.remove('open');
            _currentLabelData = null;
        }

        function buildLabelHtml(d) {
            const total = (parseFloat(d.price || 0) * parseInt(d.qty || 1)).toFixed(2);
            const phones = [d.customer_phone_01, d.customer_phone_02].filter(Boolean).join(' / ');
            const city = d.city || d.district || '';
            const state = d.destination_state_name || '';
            const waybill = d.curfox_waybill || '';
            const orderNo = d.order_code || d.call_number || '—';
            const addr = d.customer_address || '—';
            const itemCode = d.item_code || '—';
            const itemName = d.item_name || '—';
            const remark = d.remark || '';
            const dRemark = d.delivery_remark || '';
            const printDate = new Date().toLocaleDateString('en-GB');

            return `
                <div class="label-preview-header">
                    <div style="font-size:16px;font-weight:900;color:#1e3a8a">📦 Packing Label</div>
                    <div style="text-align:right">
                        ${waybill ? `<div style="font-size:13px;font-weight:700">Waybill: ${waybill}</div>` : '<div style="font-size:12px;font-weight:600;color:#dc2626">⏳ Pending Dispatch</div>'}
                        <div style="font-size:10px;color:#666">Printed: ${printDate}</div>
                    </div>
                </div>
                ${waybill ? `<div class="waybill-barcode">||| ${waybill} |||</div>` : ''}
                <div class="customer-section">
                    <div class="customer-details">
                        <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.7px;color:#444;margin-bottom:6px;padding-bottom:4px;border-bottom:1px solid #ddd">Customer Details</div>
                        <div style="margin-bottom:4px"><span style="font-size:10px;font-weight:700;color:#666;text-transform:uppercase;display:block">Name</span><span style="font-size:13px;font-weight:700">${d.customer_name || '—'}</span></div>
                        <div style="margin-bottom:4px"><span style="font-size:10px;font-weight:700;color:#666;text-transform:uppercase;display:block">Phone</span><span style="font-size:12px">${phones || '—'}</span></div>
                        <div style="margin-bottom:4px"><span style="font-size:10px;font-weight:700;color:#666;text-transform:uppercase;display:block">Address</span><span style="font-size:12px">${addr}</span></div>
                        <div><span style="font-size:10px;font-weight:700;color:#666;text-transform:uppercase;display:block">City / State</span><span style="font-size:12px;font-weight:600">${city}${state ? ', ' + state : ''}</span></div>
                    </div>
                    <div class="order-summary">
                        <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.7px;color:#444;margin-bottom:6px;padding-bottom:4px;border-bottom:1px solid #ddd">Order</div>
                        <div style="font-size:11px;font-weight:700;margin-bottom:4px">${orderNo}</div>
                        <div style="margin-top:auto;background:#000;color:#fff;padding:8px 10px;text-align:center;border-radius:4px;font-size:17px;font-weight:900">COD<br>Rs. ${total}</div>
                    </div>
                </div>
                <div class="item-row">
                    <div class="item-cell" style="flex:1;border-right:2px solid #000"><div style="font-size:9px;font-weight:700;color:#666;text-transform:uppercase">Item Code</div><div style="font-size:12px;font-weight:600">${itemCode}</div></div>
                    <div class="item-cell" style="flex:2;border-right:2px solid #000"><div style="font-size:9px;font-weight:700;color:#666;text-transform:uppercase">Item Name</div><div style="font-size:12px;font-weight:600">${itemName}</div></div>
                    <div class="item-cell" style="border-right:2px solid #000;min-width:50px"><div style="font-size:9px;font-weight:700;color:#666;text-transform:uppercase">Qty</div><div style="font-size:14px;font-weight:800">${d.qty || 1}</div></div>
                    <div class="item-cell" style="min-width:90px"><div style="font-size:9px;font-weight:700;color:#666;text-transform:uppercase">Unit Price</div><div style="font-size:12px;font-weight:700">Rs. ${parseFloat(d.price || 0).toFixed(2)}</div></div>
                </div>
                ${(remark || dRemark) ? `
                <div class="remark-section">
                    ${remark ? `<span style="font-size:10px;font-weight:700;color:#92400e">NOTE:</span> <span style="font-size:11px">${remark}</span>` : ''}
                    ${dRemark ? `${remark ? '<br>' : ''}<span style="font-size:10px;font-weight:700;color:#1e40af">DELIVERY:</span> <span style="font-size:11px">${dRemark}</span>` : ''}
                </div>` : ''}
                <div class="footer-note">Handle with care — Thank you for your order</div>
            `;
        }

        function doPrintLabel() {
            if (!_currentLabelData) return;
            printLabelInWindow([_currentLabelData]);
        }

        function doPrintAllSelected() {
            if (!_selectedLabelDatas.length) return;
            printLabelInWindow(_selectedLabelDatas);
        }

        function printLabelInWindow(dataArray) {
            const pw = window.open('', '', 'height=1000,width=820');
            pw.document.write(`<!DOCTYPE html><html><head><title>Packing Labels</title>
            <style>
                @page{margin:8mm}
                *{box-sizing:border-box;margin:0;padding:0}
                body{font-family:Arial,sans-serif;background:#fff}
                .label-wrap{page-break-after:always;margin-bottom:16px}
                .label-wrap:last-child{page-break-after:avoid;margin-bottom:0}
                .label-preview{border:2px solid #000;font-family:Arial,sans-serif;font-size:12px;color:#000;border-radius:2px}
                .label-preview-header{display:flex;align-items:center;justify-content:space-between;padding:8px 12px;border-bottom:2px solid #000;background:#f8f8f8}
                .waybill-barcode{text-align:center;padding:6px;border-bottom:2px solid #000;font-family:monospace;font-size:18px;letter-spacing:3px;background:#fff}
                .customer-section{display:flex;border-bottom:2px solid #000}
                .customer-details{flex:1;padding:10px 12px}
                .order-summary{width:160px;border-left:2px solid #000;padding:10px 12px;display:flex;flex-direction:column}
                .item-row{display:flex;border-bottom:2px solid #000}
                .item-cell{padding:7px 12px;border-right:2px solid #000}
                .item-cell:last-child{border-right:none}
                .remark-section{padding:7px 12px;background:#fffbeb;border-bottom:2px solid #000}
                .footer-note{padding:5px 12px;background:#f8f8f8;font-size:10px;color:#666;text-align:center}
            </style>
            </head><body>`);
            dataArray.forEach(d => {
                pw.document.write(`<div class="label-wrap"><div class="label-preview">${buildLabelHtml(d)}</div></div>`);
            });
            pw.document.write('</body></html>');
            pw.document.close();
            pw.focus();
            setTimeout(() => { pw.print(); pw.close(); }, 450);
        }

        // Attach print buttons
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.print-label-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    try {
                        const data = JSON.parse(this.dataset.call);
                        openPrintModal(data);
                    } catch (e) {
                        console.error('Label data parse error', e);
                        alert('Could not load label data.');
                    }
                });
            });
        });

        // Bulk print
        if (bulkPrintBtn) {
            bulkPrintBtn.addEventListener('click', function () {
                const checks = document.querySelectorAll('.rowCheck:checked');
                if (!checks.length) { alert('Select at least one row to print.'); return; }
                const datas = [];
                checks.forEach(cb => {
                    const row = cb.closest('tr');
                    const pb  = row ? row.querySelector('.print-label-btn') : null;
                    if (pb) {
                        try { datas.push(JSON.parse(pb.dataset.call)); } catch(e) {}
                    }
                });
                if (!datas.length) { alert('No printable labels found for selected rows.'); return; }
                _selectedLabelDatas = datas;
                openPrintModal(datas[0], true);
            });
        }

        // ══ STATUS UPDATE ═════════════════════════════════════════════════════

        async function doSingleUpdate(id) {
            const res = await fetch(`${BASE_URL}/${id}/status`, {
                method: 'POST',
                headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN':CSRF, 'Accept':'application/json' },
                body: JSON.stringify({})
            });
            if (!res.ok) {
                let errMsg = 'HTTP ' + res.status;
                try { const j = await res.clone().json(); errMsg = j.message || errMsg; } catch(e) {}
                throw new Error(errMsg);
            }
            return res.json();
        }

        document.querySelectorAll('.dispatch-single-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id   = this.dataset.id;
                const name = this.dataset.name;
                const city = this.dataset.city;
                const originBadge = document.getElementById('origin_badge_' + id);
                const originLabel = originBadge ? originBadge.textContent.trim() : 'Default';

                const activeBizNameEl = document.getElementById('activeBizName');
                const activeBizIdEl   = document.getElementById('activeBizId');
                const bizLabel = activeBizNameEl
                    ? `${activeBizNameEl.textContent} (${activeBizIdEl ? activeBizIdEl.textContent : ''})`
                    : 'Default';

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Dispatch to Courier?',
                        html: `<table style="width:100%;font-size:13px;text-align:left;border-collapse:collapse">
                                   <tr><td style="padding:5px 8px;color:#64748b;font-weight:600">Customer</td><td style="padding:5px 8px;font-weight:700">${name}</td></tr>
                                   <tr style="background:#f8fafc"><td style="padding:5px 8px;color:#64748b;font-weight:600">Destination</td><td style="padding:5px 8px;font-weight:700">${city}</td></tr>
                                   <tr><td style="padding:5px 8px;color:#64748b;font-weight:600">Pickup Origin</td><td style="padding:5px 8px;font-weight:700">${originLabel}</td></tr>
                                   <tr style="background:#f8fafc"><td style="padding:5px 8px;color:#64748b;font-weight:600">Business</td><td style="padding:5px 8px;font-weight:700;color:#7c3aed">${bizLabel}</td></tr>
                               </table>
                               <p style="color:#64748b;font-size:12px;margin-top:12px">This will create a waybill in the Curfox courier system.</p>`,
                        icon: 'question', showCancelButton: true,
                        confirmButtonColor: '#0284c7', cancelButtonColor: '#64748b',
                        confirmButtonText: '🚚 Dispatch Now',
                        showLoaderOnConfirm: true,
                        preConfirm: () => doSingleUpdate(id),
                        allowOutsideClick: () => !Swal.isLoading()
                    }).then(result => {
                        if (result.isConfirmed && result.value?.success) {
                            const waybill = result.value.waybill || '';
                            document.getElementById('row_tr_' + id)?.classList.add('row-ok');
                            const badge = document.getElementById('badge_' + id);
                            if (badge) badge.textContent = 'givedriver';
                            btn.textContent = '🚚 Done'; btn.disabled = true;
                            if (waybill) {
                                const td = btn.closest('tr').querySelector('td:nth-last-child(2)');
                                if (td) td.innerHTML = `<span class="waybill-badge">${waybill}</span>`;
                            }
                            Swal.fire({ icon:'success', title:'Dispatched!', text:'Waybill: ' + waybill, timer:2500, showConfirmButton:false });
                        } else if (result.value && !result.value.success) {
                            document.getElementById('row_tr_' + id)?.classList.add('row-fail');
                            Swal.fire({ icon:'error', title:'Dispatch Failed', text:result.value.message });
                        }
                    });
                } else {
                    if (!confirm(`Dispatch ${name} → ${city}\nPickup from: ${originLabel}\nBusiness: ${bizLabel}?`)) return;
                    doSingleUpdate(id).then(data => {
                        if (data.success) { document.getElementById('row_tr_' + id)?.classList.add('row-ok'); btn.disabled = true; btn.textContent = '🚚 Done'; }
                        else alert('Error: ' + data.message);
                    });
                }
            });
        });

        const bulkBtn    = document.getElementById('bulkUpdateBtn');
        const progressEl = document.getElementById('bulkProgress');

        if (bulkBtn) {
            bulkBtn.addEventListener('click', async () => {
                const selected = Array.from(document.querySelectorAll('.rowCheck:checked')).map(c => c.value);
                if (!selected.length) { alert('Please select at least one row.'); return; }

                const bizNameEl = document.getElementById('activeBizName');
                const bizIdEl   = document.getElementById('activeBizId');
                const bizInfo   = bizNameEl ? `\nBusiness: ${bizNameEl.textContent}` : '';

                const msg = nextStatus === 'givedriver'
                    ? `Dispatch ${selected.length} order(s) to Curfox?${bizInfo}`
                    : `Update ${selected.length} row(s) to "${nextStatus}"?`;
                if (!confirm(msg)) return;

                bulkBtn.disabled = true;
                let ok = 0, fail = 0;
                if (progressEl) { progressEl.style.display = 'inline'; progressEl.textContent = `0/${selected.length}`; }

                for (let i = 0; i < selected.length; i++) {
                    const id = selected[i];
                    try {
                        const data = await doSingleUpdate(id);
                        if (data.success) {
                            ok++;
                            document.getElementById('row_tr_' + id)?.classList.add('row-ok');
                            const b = document.getElementById('badge_' + id);
                            if (b && nextStatus) b.textContent = nextStatus;
                            if (data.waybill) {
                                const tr = document.getElementById('row_tr_' + id);
                                if (tr) {
                                    const dispBtn = tr.querySelector('.dispatch-single-btn');
                                    if (dispBtn) { dispBtn.textContent = '🚚 Done'; dispBtn.disabled = true; }
                                }
                            }
                        } else {
                            throw new Error(data.message || 'Unknown error');
                        }
                    } catch(e) {
                        fail++;
                        document.getElementById('row_tr_' + id)?.classList.add('row-fail');
                        console.error('Row ' + id + ' failed:', e.message);
                    }
                    if (progressEl) progressEl.textContent = `${i+1}/${selected.length} · ✓${ok} · ✕${fail}`;
                }

                const summary = `Done! ✓ Success: ${ok}  ✕ Failed: ${fail}`;
                if (typeof Swal !== 'undefined') await Swal.fire({ icon: fail > 0 ? 'warning':'success', title:'Complete', text:summary });
                else alert(summary);
                if (ok > 0) window.location.reload();
                bulkBtn.disabled = false;
            });
        }
    </script>

    @once
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    @endonce
@endsection
