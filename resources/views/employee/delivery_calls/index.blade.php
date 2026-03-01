@extends('employee.layout.app')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap');
        :root {
            --bg:#f4f6f9; --surface:#fff; --surface-2:#f8fafc; --surface-3:#f1f4f8;
            --border:#e2e8f0; --border-s:#edf0f5;
            --text:#0f172a; --text-sub:#475569; --text-muted:#94a3b8;
            --indigo:#4f46e5; --indigo-lt:#eef2ff; --indigo-bd:rgba(79,70,229,.25);
            --emerald:#059669; --emerald-lt:#ecfdf5; --emerald-bd:rgba(5,150,105,.25);
            --amber:#d97706; --amber-lt:#fffbeb; --amber-bd:rgba(217,119,6,.25);
            --sky:#0284c7; --sky-lt:#f0f9ff; --sky-bd:rgba(2,132,199,.25);
            --rose:#e11d48; --rose-lt:#fff1f2; --rose-bd:rgba(225,29,72,.25);
            --violet:#7c3aed; --violet-lt:#f5f3ff; --violet-bd:rgba(124,58,237,.25);
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
        .dc-tbadge{font-family:'JetBrains Mono',monospace;font-size:11px;padding:3px 10px;border-radius:20px;background:var(--indigo-lt);color:var(--indigo);border:1px solid var(--indigo-bd)}
        .dc-sub{display:flex;align-items:center;gap:6px;margin-top:5px;font-size:13px;color:var(--text-sub)}
        .dc-tabs{display:flex;align-items:center;gap:4px;background:var(--surface);border:1px solid var(--border);border-radius:var(--r);padding:4px;box-shadow:var(--sh-sm)}
        .dc-tab{font-size:12.5px;font-weight:600;padding:7px 18px;border-radius:7px;border:1px solid transparent;color:var(--text-sub);text-decoration:none;transition:all .16s;white-space:nowrap}
        .dc-tab:hover{background:var(--surface-3);color:var(--text)}
        .dc-tab.active{background:var(--indigo);color:#fff;box-shadow:0 2px 8px rgba(79,70,229,.28)}

        /* ── Alerts ── */
        .dc-alert{display:flex;align-items:flex-start;gap:10px;padding:12px 16px;border-radius:var(--r);font-size:13.5px;margin-bottom:14px;border:1px solid;font-weight:500}
        .dc-alert-ok{background:var(--emerald-lt);border-color:var(--emerald-bd);color:var(--emerald)}
        .dc-alert-err{background:var(--rose-lt);border-color:var(--rose-bd);color:var(--rose)}

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

        /* ── Stats ── */
        .dc-stats{display:flex;gap:12px;flex-wrap:wrap;margin-bottom:20px}
        .dc-stat{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);padding:14px 18px;box-shadow:var(--sh-sm);flex:1;min-width:130px;display:flex;align-items:center;gap:12px}
        .dc-sico{width:38px;height:38px;border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:17px;flex-shrink:0}
        .ic-ind{background:var(--indigo-lt)} .ic-em{background:var(--emerald-lt)} .ic-am{background:var(--amber-lt)} .ic-vi{background:var(--violet-lt)}
        .dc-slbl{font-size:11px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.7px}
        .dc-sval{font-size:20px;font-weight:800;color:var(--text);line-height:1;margin-top:2px;font-family:'JetBrains Mono',monospace}
        .dc-sval.em{color:var(--emerald);font-size:15px}

        /* ── Card ── */
        .dc-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r-xl);box-shadow:var(--sh);overflow:hidden}
        .dc-chead{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;padding:16px 22px;border-bottom:1px solid var(--border-s);background:var(--surface-2)}
        .dc-cbody{padding:20px 22px}
        .dc-sf{display:flex;gap:8px;flex-wrap:wrap;align-items:center}
        .dc-sw{position:relative}
        .dc-sw svg{position:absolute;left:11px;top:50%;transform:translateY(-50%);color:var(--text-muted);pointer-events:none}
        .dc-si{background:var(--surface);border:1px solid var(--border);border-radius:8px;color:var(--text);font-family:'Plus Jakarta Sans',sans-serif;font-size:13px;padding:8px 12px 8px 34px;width:300px;outline:none;transition:border-color .16s;box-shadow:var(--sh-sm)}
        .dc-si:focus{border-color:var(--indigo);box-shadow:0 0 0 3px rgba(79,70,229,.12)}

        /* ── Buttons ── */
        .btn-dc{font-family:'Plus Jakarta Sans',sans-serif;font-size:13px;font-weight:600;padding:8px 18px;border-radius:8px;border:1px solid transparent;cursor:pointer;transition:all .16s;text-decoration:none;display:inline-flex;align-items:center;gap:6px;line-height:1}
        .btn-ind{background:var(--indigo);color:#fff;box-shadow:0 1px 4px rgba(79,70,229,.3)}
        .btn-ind:hover{background:#4338ca;color:#fff}
        .btn-ghost{background:var(--surface);border-color:var(--border);color:var(--text-sub);box-shadow:var(--sh-sm)}
        .btn-ghost:hover{background:var(--surface-3);color:var(--text)}
        .btn-violet{background:var(--violet);color:#fff;box-shadow:0 1px 4px rgba(124,58,237,.3)}
        .btn-violet:hover{background:#6d28d9;color:#fff}
        .btn-bulk{background:var(--emerald-lt);border-color:var(--emerald-bd);color:var(--emerald)}
        .btn-bulk:hover{background:var(--emerald);color:#fff;border-color:var(--emerald)}
        .btn-bulk:disabled{opacity:.45;cursor:not-allowed}
        .btn-dispatch{background:#0284c7;border-color:rgba(2,132,199,.3);color:#fff}
        .btn-dispatch:hover{background:#0369a1;color:#fff}
        .btn-sm-view{font-size:12px;font-weight:600;padding:5px 10px;border-radius:7px;border:1px solid var(--sky-bd);background:var(--sky-lt);color:var(--sky);cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;transition:all .15s}
        .btn-sm-view:hover{background:var(--sky);color:#fff;border-color:var(--sky)}
        .btn-sm-next{font-size:12px;font-weight:600;padding:5px 10px;border-radius:7px;border:1px solid var(--indigo-bd);background:var(--indigo-lt);color:var(--indigo);cursor:pointer;display:inline-flex;align-items:center;transition:all .15s}
        .btn-sm-next:hover{background:var(--indigo);color:#fff;border-color:var(--indigo)}
        .btn-sm-dispatch{font-size:12px;font-weight:700;padding:5px 10px;border-radius:7px;border:1px solid rgba(2,132,199,.3);background:#f0f9ff;color:#0284c7;cursor:pointer;display:inline-flex;align-items:center;transition:all .15s}
        .btn-sm-dispatch:hover{background:#0284c7;color:#fff}
        .btn-sm-final{font-size:12px;font-weight:600;padding:5px 10px;border-radius:7px;border:1px solid var(--border);background:var(--surface-3);color:var(--text-muted);pointer-events:none;display:inline-flex;align-items:center}

        /* ── Bulk bars ── */
        .dc-bulk{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;padding:11px 16px;background:var(--indigo-lt);border:1px solid var(--indigo-bd);border-radius:var(--r);margin-bottom:16px}
        .dc-bulk-l{display:flex;align-items:center;gap:12px;flex-wrap:wrap}

        /* ── Checkboxes ── */
        input[type=checkbox].chk-main,input[type=checkbox].rowCheck{appearance:none;-webkit-appearance:none;width:16px;height:16px;border-radius:5px;border:1.5px solid var(--border);background:var(--surface);cursor:pointer;position:relative;transition:all .14s;flex-shrink:0;vertical-align:middle}
        input[type=checkbox].chk-main:checked,input[type=checkbox].rowCheck:checked{background:var(--indigo);border-color:var(--indigo)}
        input[type=checkbox].chk-main:checked::after,input[type=checkbox].rowCheck:checked::after{content:'';position:absolute;left:4px;top:1.5px;width:5px;height:9px;border:2px solid #fff;border-top:none;border-left:none;transform:rotate(45deg)}
        .dc-tpill{font-size:12px;font-weight:600;color:var(--text-sub);background:var(--surface);padding:4px 14px;border-radius:20px;border:1px solid var(--border);font-family:'JetBrains Mono',monospace}

        /* ── Table ── */
        .dc-twrap{border-radius:var(--r);overflow:hidden;border:1px solid var(--border)}
        table.dc-t{width:100%;border-collapse:collapse;font-size:13px}
        table.dc-t thead{background:var(--surface-2);border-bottom:2px solid var(--border)}
        table.dc-t thead th{font-size:11px;font-weight:700;letter-spacing:.7px;text-transform:uppercase;color:var(--text-muted);padding:11px 13px;white-space:nowrap;text-align:left;border-right:1px solid var(--border-s)}
        table.dc-t thead th:last-child{border-right:none}
        table.dc-t tbody tr{border-bottom:1px solid var(--border-s);transition:background .1s}
        table.dc-t tbody tr:last-child{border-bottom:none}
        table.dc-t tbody tr:hover{background:#f8f9ff}
        table.dc-t tbody td{padding:11px 13px;color:var(--text);vertical-align:middle;border-right:1px solid var(--border-s)}
        table.dc-t tbody td:last-child{border-right:none}
        table.dc-t tfoot tr{background:var(--surface-2);border-top:2px solid var(--border)}
        table.dc-t tfoot td{padding:10px 13px;font-family:'JetBrains Mono',monospace}

        /* ── Cell styles ── */
        .c-id{font-family:'JetBrains Mono',monospace;font-size:11px;color:var(--text-muted)}
        .c-call{font-family:'JetBrains Mono',monospace;font-size:12.5px;font-weight:500;color:var(--indigo)}
        .c-oc{font-family:'JetBrains Mono',monospace;font-size:11px;background:var(--amber-lt);padding:2px 7px;border-radius:4px;border:1px solid var(--amber-bd);color:var(--amber);white-space:nowrap}
        .c-date{font-family:'JetBrains Mono',monospace;font-size:11.5px;color:var(--text-sub)}
        .c-name{font-weight:700}
        .c-ph{font-family:'JetBrains Mono',monospace;font-size:12px}
        .c-loc{font-size:12px;color:var(--text-sub)}
        .c-total{font-family:'JetBrains Mono',monospace;font-size:13px;font-weight:700;color:var(--emerald);display:block;text-align:right}

        /* ── Badges ── */
        .dc-badge{font-size:10.5px;font-weight:700;letter-spacing:.5px;text-transform:uppercase;padding:3px 9px;border-radius:20px;display:inline-flex;align-items:center;gap:5px;border:1px solid;white-space:nowrap}
        .dc-badge::before{content:'';width:5px;height:5px;border-radius:50%;background:currentColor;opacity:.7}
        .badge-pending,.dc-badge-pending{background:var(--amber-lt);border-color:var(--amber-bd);color:var(--amber)}
        .badge-packing{background:var(--sky-lt);border-color:var(--sky-bd);color:var(--sky)}
        .badge-packed{background:var(--violet-lt);border-color:var(--violet-bd);color:var(--violet)}
        .badge-give-driver,.badge-givedriver{background:var(--emerald-lt);border-color:var(--emerald-bd);color:var(--emerald)}
        .waybill-badge{font-family:'JetBrains Mono',monospace;font-size:11px;background:#f0f9ff;color:#0284c7;padding:3px 8px;border-radius:5px;border:1px solid rgba(2,132,199,.2)}
        .no-city-warn{font-size:11px;background:#fef3c7;color:#92400e;padding:2px 7px;border-radius:4px;border:1px solid #fde68a}
        .curfox-error{font-size:11px;color:var(--rose);background:var(--rose-lt);padding:3px 8px;border-radius:5px;border:1px solid var(--rose-bd);margin-top:4px;display:block}
        tbody tr.row-ok td{background:#f0fdf4!important}
        tbody tr.row-fail td{background:#fff1f2!important}

        /* ── Empty state ── */
        .dc-empty{text-align:center;padding:56px 0}
        .dc-empty-ico{width:52px;height:52px;background:var(--surface-3);border-radius:13px;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;font-size:21px;border:1px solid var(--border)}

        /* ── Pagination ── */
        nav[aria-label] span[aria-current=page]>span,nav[aria-label] a,nav[aria-label] button{background:var(--surface)!important;border:1px solid var(--border)!important;color:var(--text-sub)!important;border-radius:7px!important;font-family:'JetBrains Mono',monospace!important;font-size:12px!important;padding:5px 12px!important;display:inline-flex!important;align-items:center!important;justify-content:center!important;min-width:34px;height:32px;text-decoration:none;line-height:1!important;transition:all .14s}
        nav[aria-label] a:hover{background:var(--indigo)!important;border-color:var(--indigo)!important;color:#fff!important}
        nav[aria-label] span[aria-current=page]>span{background:var(--indigo)!important;border-color:var(--indigo)!important;color:#fff!important}
        nav[aria-label]>div:first-child{display:none}
        nav[aria-label] .flex{gap:4px;flex-wrap:wrap;align-items:center}

        /* ── Origin badge & edit ── */
        .origin-wrap{display:flex;align-items:center;gap:4px}
        .origin-badge{display:inline-flex;align-items:center;gap:4px;font-family:'JetBrains Mono',monospace;font-size:11px;font-weight:600;padding:3px 8px;border-radius:5px;background:var(--sky-lt);color:var(--sky);border:1px solid var(--sky-bd);white-space:nowrap;line-height:1.3}
        .origin-badge.is-default{background:var(--amber-lt);color:var(--amber);border-color:var(--amber-bd)}
        .origin-badge .def-tag{font-size:9px;opacity:.75;font-weight:500}
        .origin-edit-btn{background:none;border:1px solid transparent;cursor:pointer;padding:3px 5px;border-radius:5px;color:var(--text-muted);line-height:1;transition:all .14s;font-size:13px;flex-shrink:0}
        .origin-edit-btn:hover{background:var(--indigo-lt);border-color:var(--indigo-bd);color:var(--indigo)}

        /* ── Bulk origin bar ── */
        .bulk-origin-bar{display:none;align-items:center;gap:10px;flex-wrap:wrap;padding:10px 14px;background:var(--amber-lt);border:1px solid var(--amber-bd);border-radius:var(--r);margin-bottom:12px}
        .bulk-origin-bar.show{display:flex}

        /* ── City autocomplete ── */
        .city-ac-wrap{position:relative}
        .city-ac-input{width:100%;border:1px solid var(--border);border-radius:8px;padding:9px 12px;font-family:'Plus Jakarta Sans',sans-serif;font-size:13px;color:var(--text);outline:none;background:var(--surface);transition:border-color .15s}
        .city-ac-input:focus{border-color:var(--indigo);box-shadow:0 0 0 3px rgba(79,70,229,.1)}
        .city-ac-drop{display:none;position:absolute;top:calc(100% + 4px);left:0;right:0;background:var(--surface);border:1px solid var(--border);border-radius:8px;box-shadow:var(--sh);z-index:200;max-height:210px;overflow-y:auto}
        .city-ac-drop.open{display:block}
        .city-ac-item{display:flex;align-items:center;justify-content:space-between;padding:9px 12px;cursor:pointer;transition:background .1s}
        .city-ac-item:hover{background:var(--indigo-lt)}
        .city-ac-name{font-size:13px;font-weight:600;color:var(--text)}
        .city-ac-state{font-size:11px;color:var(--text-muted);font-family:'JetBrains Mono',monospace}
        .city-ac-empty{padding:10px 12px;font-size:13px;color:var(--text-muted);pointer-events:none}

        /* ── Origin modal ── */
        .om-overlay{display:none;position:fixed;inset:0;background:rgba(15,23,42,.55);z-index:1000;align-items:center;justify-content:center;padding:20px;backdrop-filter:blur(2px)}
        .om-overlay.open{display:flex}
        .om-box{background:var(--surface);border-radius:var(--r-xl);box-shadow:0 24px 64px rgba(15,23,42,.2);width:100%;max-width:460px;overflow:hidden;animation:omUp .2s cubic-bezier(.16,1,.3,1)}
        @keyframes omUp{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)}}
        .om-head{display:flex;align-items:center;justify-content:space-between;padding:18px 22px;border-bottom:1px solid var(--border);background:var(--surface-2)}
        .om-head h3{font-size:15px;font-weight:800;color:var(--text);display:flex;align-items:center;gap:8px}
        .om-close{background:none;border:none;cursor:pointer;font-size:19px;color:var(--text-muted);line-height:1;padding:2px 5px;border-radius:5px;transition:all .14s}
        .om-close:hover{background:var(--rose-lt);color:var(--rose)}
        .om-body{padding:22px}
        .om-foot{display:flex;gap:10px;justify-content:flex-end;padding:14px 22px;border-top:1px solid var(--border);background:var(--surface-2)}
        .om-note{font-size:12.5px;color:var(--text-sub);line-height:1.6;padding:10px 12px;background:var(--sky-lt);border:1px solid var(--sky-bd);border-radius:7px;margin-bottom:18px}
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
        .biz-card.selected .biz-card-ico{background:var(--violet);border-color:var(--violet)}
        .biz-card-name{font-size:14px;font-weight:700;color:var(--text)}
        .biz-card-meta{font-family:'JetBrains Mono',monospace;font-size:11px;color:var(--text-muted);margin-top:2px}
        .biz-card-check{width:20px;height:20px;border-radius:50%;background:var(--violet);border:none;display:flex;align-items:center;justify-content:center;font-size:11px;color:#fff;margin-left:auto;flex-shrink:0;display:none}
        .biz-card.selected .biz-card-check{display:flex}
        .biz-card-default{font-size:10px;font-weight:700;background:var(--emerald-lt);color:var(--emerald);border:1px solid var(--emerald-bd);padding:1px 6px;border-radius:10px;margin-left:6px;vertical-align:middle}
    </style>

    <div class="dc-page">
        <div class="dc-wrap">

            {{-- ── HEADER ── --}}
            <div class="dc-hdr">
                <div>
                    <div class="dc-crumb">Employee Portal › Delivery Calls › <span style="color:var(--indigo);font-weight:700">{{ $status }}</span></div>
                    <div class="dc-title">
                        Delivery Calls <span class="dc-tbadge">{{ $status }}</span>
                        @if($status === 'packed')
                            <span style="font-size:12px;font-weight:600;color:var(--sky);background:var(--sky-lt);padding:3px 10px;border-radius:20px;border:1px solid var(--sky-bd)">🚚 Next: Dispatch to Courier</span>
                        @endif
                    </div>
                    <div class="dc-sub">
                        Showing <strong>{{ $status }}</strong> records
                        @if($nextStatus)
                            → Next: <span style="font-weight:700;color:var(--indigo)">{{ $nextStatus }}</span>
                            @if($nextStatus === 'givedriver')<span style="color:var(--sky);font-weight:700">(Curfox API dispatch)</span>@endif
                        @else
                            <span style="color:var(--emerald);font-weight:700">✓ Final Stage</span>
                        @endif
                    </div>
                </div>
                <div class="dc-tabs">
                    <a class="dc-tab {{ request()->routeIs('employee.delivery_calls.pending')    ? 'active':'' }}" href="{{ route('employee.delivery_calls.pending') }}">Pending</a>
                    <a class="dc-tab {{ request()->routeIs('employee.delivery_calls.packing')    ? 'active':'' }}" href="{{ route('employee.delivery_calls.packing') }}">Packing</a>
                    <a class="dc-tab {{ request()->routeIs('employee.delivery_calls.packed')     ? 'active':'' }}" href="{{ route('employee.delivery_calls.packed') }}">Packed</a>
                    <a class="dc-tab {{ request()->routeIs('employee.delivery_calls.givedriver') ? 'active':'' }}" href="{{ route('employee.delivery_calls.givedriver') }}">Give Driver</a>
                </div>
            </div>

            @if(session('success'))<div class="dc-alert dc-alert-ok">✓ &nbsp;{{ session('success') }}</div>@endif
            @if(session('error'))<div class="dc-alert dc-alert-err">✕ &nbsp;{{ session('error') }}</div>@endif

            {{-- ── BUSINESS BANNER ── --}}
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
                        <form action="{{ route('employee.delivery_calls.set_business') }}" method="POST" class="biz-form">
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

            @php $pageSubtotal = $calls->sum(fn($c) => ($c->price ?? 0) * ($c->qty ?? 1)); @endphp

            {{-- ── STATS ── --}}
            <div class="dc-stats">
                <div class="dc-stat"><div class="dc-sico ic-ind">📦</div><div><div class="dc-slbl">Total Records</div><div class="dc-sval">{{ $calls->total() }}</div></div></div>
                <div class="dc-stat"><div class="dc-sico ic-am">📄</div><div><div class="dc-slbl">This Page</div><div class="dc-sval">{{ $calls->count() }}</div></div></div>
                <div class="dc-stat"><div class="dc-sico ic-vi">🗂</div><div><div class="dc-slbl">Stage</div><div class="dc-sval" style="font-size:14px;margin-top:3px">{{ $status }}</div></div></div>
                <div class="dc-stat"><div class="dc-sico ic-em">💰</div><div><div class="dc-slbl">Page Subtotal</div><div class="dc-sval em">Rs. {{ number_format($pageSubtotal, 2) }}</div></div></div>
                @if($status === 'givedriver')
                    <div class="dc-stat"><div class="dc-sico" style="background:#f0f9ff">🚀</div>
                        <div><div class="dc-slbl">Dispatched</div>
                            <div class="dc-sval" style="font-size:14px;margin-top:3px;color:#0284c7">{{ $calls->where('curfox_waybill', '!=', null)->count() }} / {{ $calls->count() }}</div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- ── MAIN CARD ── --}}
            <div class="dc-card">
                <div class="dc-chead">
                    <form class="dc-sf" method="GET" action="{{ url()->current() }}">
                        <div class="dc-sw">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                            <input type="text" name="q" class="dc-si" value="{{ request('q') }}" placeholder="Search order code, customer, phone…">
                        </div>
                        <button class="btn-dc btn-ind" type="submit">Search</button>
                        <a class="btn-dc btn-ghost" href="{{ url()->current() }}">Reset</a>
                    </form>
                    <div style="font-size:12px;color:var(--text-muted);font-weight:500">Page {{ $calls->currentPage() }} / {{ $calls->lastPage() }}</div>
                </div>

                <div class="dc-cbody">

                    {{-- BULK ORIGIN BAR --}}
                    <div class="bulk-origin-bar" id="bulkOriginBar">
                        <span style="font-size:13px;font-weight:700;color:var(--amber)">📍 Set pickup origin for selected rows:</span>
                        <div class="city-ac-wrap" style="flex:1;min-width:180px;max-width:260px">
                            <input type="text" id="bulkOriginInput" class="city-ac-input" placeholder="Type city name…" autocomplete="off">
                            <div class="city-ac-drop" id="bulkOriginDrop"></div>
                        </div>
                        <button id="bulkOriginApplyBtn" class="btn-dc btn-ind" style="padding:8px 16px" disabled>Apply to Selected</button>
                    </div>

                    {{-- BULK STATUS BAR --}}
                    <div class="dc-bulk">
                        <div class="dc-bulk-l">
                            <label style="display:flex;align-items:center;gap:8px;font-size:13px;font-weight:600;color:var(--indigo);cursor:pointer;user-select:none">
                                <input type="checkbox" class="chk-main" id="selectAll"> Select All
                            </label>
                            @if($nextStatus)
                                <button type="button" id="bulkUpdateBtn" class="btn-dc {{ $nextStatus === 'givedriver' ? 'btn-dispatch' : 'btn-bulk' }}">
                                    @if($nextStatus === 'givedriver') 🚚 Bulk Dispatch to Curfox → Give Driver
                                    @else Bulk Update → {{ $nextStatus }}
                                    @endif
                                </button>
                                <span id="bulkProgress" style="display:none;font-size:12px;font-weight:600;color:var(--indigo);background:#fff;padding:4px 12px;border-radius:20px;border:1px solid var(--indigo-bd);font-family:'JetBrains Mono',monospace"></span>
                            @else
                                <span class="btn-sm-final">Final Status</span>
                            @endif
                        </div>
                        <span class="dc-tpill">{{ $calls->total() }} total</span>
                    </div>

                    {{-- TABLE --}}
                    <div class="dc-twrap">
                        <table class="dc-t">
                            <thead>
                            <tr>
                                <th style="width:44px;text-align:center"></th>
                                <th>ID</th>
                                <th>Call No</th>
                                <th>Order Code</th>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Phone</th>
                                <th>Destination</th>
                                <th>Origin 📍</th>
                                <th>Item</th>
                                <th style="text-align:center">Qty</th>
                                <th style="text-align:right">Total</th>
                                <th>Status</th>
                                @if($status === 'givedriver')<th>Waybill</th>@endif
                                <th style="width:160px">Actions</th>
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
                                @endphp
                                <tr id="row_tr_{{ $c->id }}" class="{{ $c->curfox_waybill ? 'row-ok' : '' }}">
                                    <td style="text-align:center">
                                        <input type="checkbox" class="rowCheck" value="{{ $c->id }}">
                                    </td>
                                    <td><span class="c-id">{{ $c->id }}</span></td>
                                    <td><span class="c-call">{{ $c->call_number }}</span></td>
                                    <td>@if($c->order_code)<span class="c-oc">{{ $c->order_code }}</span>@else<span style="color:var(--text-muted)">—</span>@endif</td>
                                    <td><span class="c-date">{{ optional($c->date)->format('Y-m-d') }}</span></td>
                                    <td>
                                        <div class="c-name">{{ $c->customer_name }}</div>
                                        <div style="font-size:11px;color:var(--text-muted)">{{ $c->customer_address }}</div>
                                    </td>
                                    <td>
                                        <div class="c-ph">{{ $c->customer_phone_01 }}</div>
                                        @if($c->customer_phone_02)<div style="font-family:monospace;font-size:10.5px;color:var(--text-muted)">{{ $c->customer_phone_02 }}</div>@endif
                                    </td>

                                    {{-- Destination --}}
                                    <td>
                                        @if($c->city || $c->district)
                                            <span class="c-loc">{{ $c->city }}@if($c->city && $c->district) / @endif{{ $c->city ? '' : $c->district }}</span>
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
                                            <span class="origin-badge {{ $hasCustom ? '' : 'is-default' }}"
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

                                    <td><span style="font-size:13px">{{ $c->item_name }}</span></td>
                                    <td style="text-align:center;font-family:monospace;font-weight:700;font-size:14px">{{ $c->qty }}</td>
                                    <td><span class="c-total">Rs. {{ number_format($lineTotal, 2) }}</span></td>
                                    <td>
                                        <span class="dc-badge badge-{{ strtolower(str_replace(' ','-',$c->status)) }}" id="badge_{{ $c->id }}">
                                            {{ $c->status }}
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
                                            <a class="btn-sm-view" href="{{ route('employee.delivery_calls.show', $c->id) }}">View</a>
                                            @if($nextStatus)
                                                @if($nextStatus === 'givedriver')
                                                    <button class="btn-sm-dispatch dispatch-single-btn"
                                                            data-id="{{ $c->id }}"
                                                            data-name="{{ $c->customer_name }}"
                                                            data-city="{{ $c->city ?? $c->district ?? 'Unknown' }}"
                                                            {{ $c->curfox_waybill ? 'disabled' : '' }}
                                                            type="button">
                                                        🚚 {{ $c->curfox_waybill ? 'Done' : 'Dispatch' }}
                                                    </button>
                                                @else
                                                    <form method="POST"
                                                          action="{{ route('employee.delivery_calls.update_status', $c->id) }}"
                                                          onsubmit="return confirm('Update to {{ $nextStatus }}?')"
                                                          style="display:contents">
                                                        @csrf
                                                        <button class="btn-sm-next" type="submit">→ {{ $nextStatus }}</button>
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
                                    <td colspan="{{ $status === 'givedriver' ? 16 : 15 }}">
                                        <div class="dc-empty">
                                            <div class="dc-empty-ico">📭</div>
                                            <div style="font-size:15px;font-weight:700;color:var(--text);margin-bottom:5px">No records found</div>
                                            <div style="font-size:13px;color:var(--text-muted)">No {{ $status }} delivery calls match your search.</div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                            @if($calls->count())
                                <tfoot>
                                <tr>
                                    <td colspan="{{ $status === 'givedriver' ? 11 : 10 }}" style="text-align:right;padding:11px 13px">
                                        <span style="font-size:11px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.7px">Page Subtotal</span>
                                    </td>
                                    <td style="text-align:right;padding:11px 13px">
                                        <span style="font-size:14px;font-weight:800;color:var(--emerald)">Rs. {{ number_format($pageSubtotal, 2) }}</span>
                                    </td>
                                    <td colspan="{{ $status === 'givedriver' ? 4 : 3 }}"></td>
                                </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>

                    {{-- PAGINATION --}}
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

    {{-- ══ ORIGIN EDIT MODAL ══ --}}
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
                    The wrong origin will cause a <em>"no rate card"</em> error.
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
                <button class="btn-dc btn-ind" id="omSaveBtn" onclick="saveOriginModal()" disabled>Save Origin</button>
            </div>
        </div>
    </div>

    {{-- ══ BUSINESS SELECT MODAL ══ --}}
    <div class="bm-overlay" id="bizModal">
        <div class="bm-box">
            <div class="bm-head">
                <h3>🏢 Select Active Business</h3>
                <button class="bm-close" onclick="closeBizModal()">✕</button>
            </div>
            <div class="bm-body">
                <div class="bm-note">
                    The selected business determines which <strong>Curfox merchant account</strong> is used when
                    dispatching orders to the courier. All dispatch calls in this session will use this business ID.
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

    {{-- Hidden form for business POST --}}
    <form id="bizSetForm" action="{{ route('employee.delivery_calls.set_business') }}" method="POST" style="display:none">
        @csrf
        <input type="hidden" name="merchant_business_id" id="bizFormInput">
    </form>

    <script>
        // ── Constants ─────────────────────────────────────────────────────────
        const CURFOX_CITIES = @json($curfoxCities);
        const CSRF           = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const BASE_URL       = '{{ url("/employee/delivery-calls") }}';
        const BULK_ORIG_URL  = '{{ route("employee.delivery_calls.bulk_origin") }}';
        const nextStatus     = @json($nextStatus);

        // ── City autocomplete helper ──────────────────────────────────────────
        function filterCities(term) {
            if (!term || term.length < 1) return [];
            const t = term.toLowerCase();
            return CURFOX_CITIES.filter(c =>
                c.name.toLowerCase().includes(t) || c.state_name.toLowerCase().includes(t)
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
                    d.innerHTML = `<span class="city-ac-name">${c.name}</span><span class="city-ac-state">${c.state_name}</span>`;
                    d.addEventListener('mousedown', e => { e.preventDefault(); onPick(c); });
                    dropEl.appendChild(d);
                });
            }
            dropEl.classList.add('open');
        }

        document.addEventListener('click', () => {
            document.querySelectorAll('.city-ac-drop').forEach(d => d.classList.remove('open'));
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
                badge.innerHTML = cityName;
                badge.classList.remove('is-default');
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
            // Deselect all
            document.querySelectorAll('.biz-card').forEach(c => {
                c.classList.remove('selected');
                c.querySelector('.biz-card-ico').textContent = '🏬';
            });
            // Select clicked
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

            // Optimistically update banner (won't matter — page will reload)
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
                document.getElementById('omStateName').value = city.state_name;
                document.getElementById('omSelectedDisplay').textContent = `${city.name} — ${city.state_name}`;
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

        document.getElementById('bulkOriginInput').addEventListener('input', function() {
            bulkCitySelected = null;
            document.getElementById('bulkOriginApplyBtn').disabled = true;
            if (!this.value.trim()) { document.getElementById('bulkOriginDrop').classList.remove('open'); return; }
            renderDrop(filterCities(this.value), document.getElementById('bulkOriginDrop'), city => {
                bulkCitySelected = city;
                document.getElementById('bulkOriginInput').value = city.name;
                document.getElementById('bulkOriginDrop').classList.remove('open');
                document.getElementById('bulkOriginApplyBtn').disabled = false;
            });
        });

        document.getElementById('bulkOriginApplyBtn').addEventListener('click', async () => {
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
                        origin_state_name: bulkCitySelected.state_name
                    })
                });
                const data = await res.json();
                if (data.success) {
                    ids.forEach(id => applyOriginToRow(id, bulkCitySelected.id, bulkCitySelected.name, bulkCitySelected.state_name));
                    toast(`✓ ${data.message}`);
                } else { toast('✕ ' + data.message, false); }
            } catch(e) { toast('✕ Network error', false); }
            finally { btn.disabled = false; btn.textContent = 'Apply to Selected'; }
        });

        // ══ CHECKBOXES ════════════════════════════════════════════════════════

        const selectAll     = document.getElementById('selectAll');
        const bulkOriginBar = document.getElementById('bulkOriginBar');

        function syncCheckboxes() {
            const all = Array.from(document.querySelectorAll('.rowCheck'));
            const chk = all.filter(c => c.checked);
            if (selectAll) {
                selectAll.indeterminate = chk.length > 0 && chk.length < all.length;
                selectAll.checked       = chk.length > 0 && chk.length === all.length;
            }
            bulkOriginBar.classList.toggle('show', chk.length > 0);
        }

        if (selectAll) selectAll.addEventListener('change', () => {
            document.querySelectorAll('.rowCheck').forEach(c => c.checked = selectAll.checked);
            syncCheckboxes();
        });
        document.querySelectorAll('.rowCheck').forEach(c => c.addEventListener('change', syncCheckboxes));

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

                // Get active business name for confirmation dialog
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
                            // Update waybill cell if dispatched
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
    @endonce
@endsection
