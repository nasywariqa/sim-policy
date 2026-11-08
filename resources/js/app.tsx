import React, { useCallback, useEffect, useState } from 'react';
import { createRoot } from 'react-dom/client';
import { BrowserRouter, Link, Navigate, NavLink, Route, Routes, useNavigate } from 'react-router-dom';
import { api, errorText, type Me } from './api';
import { DashboardPage, OrganizationPage, ProgramsPage, SawPage } from './pages';

function Logo({ small = false }: { small?: boolean }) {
  return <span className="brand"><span className="brand-symbol">{'<'}/<b>{'>'}</b></span>{!small && <span>POLYTECHNIC<span className="brand-accent"> LINUX</span><small>COMMUNITY PLATFORM</small></span>}</span>;
}

function Landing() {
  return <div className="landing">
    <header className="public-nav wrap"><a href="#beranda" aria-label="Beranda PLC"><Logo /></a><nav className="public-links"><a href="#beranda">Beranda</a><a href="#fitur">Fitur</a><a href="#tentang">Tentang</a></nav><Link className="btn btn-primary btn-sm" to="/login">Masuk <span>↗</span></Link></header>
    <main>
      <section className="hero wrap" id="beranda"><div className="hero-copy"><div className="eyebrow"><span className="eyebrow-dot"/> PLATFORM MANAJEMEN ORGANISASI</div><h1>MANAGE.<br/>EVALUATE.<br/><span>EVOLVE.</span></h1><p>Satu sistem untuk mengelola kepengurusan, program kerja, evaluasi, dan prioritas kegiatan Polytechnic Linux Community.</p><div className="hero-actions"><Link className="btn btn-primary" to="/login">Masuk ke Sistem <span>↗</span></Link><a className="text-link" href="#fitur">Jelajahi Fitur <span>↓</span></a></div><div className="hero-footnote">ORGANIZED TO BUILD BETTER.</div></div>
      <div className="hero-art"><div className="hero-glow"/><div className="preview"><div className="preview-sidebar"><div className="preview-dot orange"/><div className="preview-rail active"/><div className="preview-rail"/><div className="preview-rail"/><div className="preview-rail"/></div><div className="preview-content"><div className="preview-heading"><span>Dashboard / Overview</span><span className="preview-avatar">PLC</span></div><div className="preview-title">Organization Overview</div><div className="preview-kpis"><div><small>Program Kerja</small><strong>—</strong><span className="tinybar"/></div><div><small>Evaluasi</small><strong>—</strong><span className="tinybar lighter"/></div><div><small>Ranking SAW</small><strong>—</strong><span className="tinybar"/></div></div><div className="preview-chart"><small>Activity Overview</small><div className="bars">{[34,49,39,70,58,84,67,92,71,83,61,76].map((v,i)=><i key={i} style={{height:`${v}%`}} />)}</div></div><div className="preview-footer"><span>Program Kerja</span><span className="status-tag submitted">Diajukan</span></div></div></div><div className="preview-caption">DASHBOARD PREVIEW · ILUSTRASI ANTARMUKA</div></div></section>
      <section className="features-section" id="fitur"><div className="wrap"><div className="section-kicker">01 / WHAT WE DO</div><div className="section-top"><h2>BUILT TO MAKE<br/><span>THINGS MOVE.</span></h2><p>Dari ide sampai keputusan, semuanya dalam satu alur yang jelas dan terdokumentasi.</p></div><div className="features-grid"><article className="feature"><div className="feature-number">01</div><div className="feature-icon">▦</div><h3>PROGRAM KERJA</h3><p>Susun draft, ajukan kegiatan, kelola PIC, dan pantau status program kerja bidang.</p></article><article className="feature"><div className="feature-number">02</div><div className="feature-icon">◈</div><h3>EVALUASI & SAW</h3><p>Nilai usulan secara terstruktur dan lihat prioritas kegiatan menggunakan metode SAW.</p></article><article className="feature"><div className="feature-number">03</div><div className="feature-icon">⌘</div><h3>MANAJEMEN ORGANISASI</h3><p>Kelola pengguna, periode kepengurusan, bidang, dan kewenangan akses.</p></article></div></div></section>
      <section className="closing-cta" id="tentang"><div className="wrap cta-row"><div><div className="section-kicker">02 / LET'S GET ORGANIZED</div><h2>BETTER SYSTEM.<br/>BIGGER IMPACT.</h2><p>Langkah berikutnya dimulai dari pengelolaan yang lebih baik.</p></div><Link className="btn btn-light" to="/login">Mulai Sekarang <span>↗</span></Link></div></section>
    </main><footer className="public-footer wrap"><Logo/><span>© {new Date().getFullYear()} Polytechnic Linux Community</span><a href="#beranda">Back to top ↑</a></footer>
  </div>;
}

function Login({ onLogin }: { onLogin: () => Promise<void> }) {
  const navigate = useNavigate();
  const [email,setEmail]=useState(''); const [password,setPassword]=useState('');
  const [loading,setLoading]=useState(false); const [error,setError]=useState('');
  async function submit(e: React.FormEvent) {
    e.preventDefault(); setLoading(true);setError('');
    try { await api.get('/sanctum/csrf-cookie'); await api.post('/login',{email,password}); await onLogin(); navigate('/app',{replace:true}); }
    catch(err) { setError(errorText(err)); }
    finally { setLoading(false); }
  }
  return <div className="login-view"><div className="login-top"><Link to="/"><Logo /></Link><Link className="text-link" to="/">← Kembali ke beranda</Link></div><div className="login-inner"><div className="login-decor"><span className="section-kicker">THE PLC PLATFORM</span><h1>WELCOME<br/><span>BACK.</span></h1><p>Organize your work. Make every decision count.</p><div className="login-orb"/></div><form className="login-form panel" onSubmit={submit}><div className="eyebrow">AREA PENGURUS</div><h2>Masuk ke Sistem</h2><p>Gunakan akun yang diberikan oleh administrator.</p><label>Email<input autoComplete="username" type="email" required value={email} onChange={e=>setEmail(e.target.value)} placeholder="nama@contoh.com" /></label><label>Password<input autoComplete="current-password" type="password" required value={password} onChange={e=>setPassword(e.target.value)} placeholder="Masukkan password" /></label>{error&&<div className="alert error" role="alert">{error}</div>}<button className="btn btn-primary btn-block" type="submit" disabled={loading}>{loading?'Memproses…':'Masuk'} <span>↗</span></button><div className="login-hint">Belum memiliki akun? Hubungi Super Admin.</div></form></div></div>;
}

function DashboardShell({me,onLogout}:{me:Me;onLogout:()=>Promise<void>}) {
  const links=[{to:'/app',label:'Dashboard',icon:'▦',show:true,end:true},
  {to:'/app/programs',label:'Program Kerja',icon:'▤',show:!!me.membership,end:false},
  {to:'/app/saw',label:'Penilaian & SAW',icon:'◈',show:me.permissions.includes('saw.view'),end:false},
  {to:'/app/organization',label:'Administrasi',icon:'⌘',show:me.is_super_admin,end:false}];
  return <div className="shell"><aside className="sidebar"><Link className="sidebar-logo" to="/app"><Logo /></Link><div className="sidebar-label">WORKSPACE</div><nav>{links.filter(l=>l.show).map(l=><NavLink end={l.end} to={l.to} key={l.to} className={({isActive})=>'sidebar-link'+(isActive?' selected':'')}><span>{l.icon}</span>{l.label}</NavLink>)}</nav><div className="sidebar-bottom"><div className="period-chip"><small>PERIODE AKTIF</small><strong>{me.period?.name??'Belum ada'}</strong></div><div className="sidebar-user"><div className="avatar">{me.name.slice(0,1)}</div><div className="user-info"><strong>{me.name}</strong><small>{me.is_super_admin?'Super Admin':me.membership?.role_name??'Tanpa keanggotaan'}</small></div></div><button className="sidebar-logout" onClick={onLogout}>↪ &nbsp; Keluar</button></div></aside><div className="shell-main"><header className="topbar"><div><span className="topbar-path">WORKSPACE</span><span className="slash">/</span><span className="topbar-current">PLC Management</span></div><div className="topbar-right"><span className="online-dot"/> <span>System Online</span><span className="avatar mini">{me.name.slice(0,1)}</span></div></header><div className="content"><Routes><Route index element={<DashboardPage me={me}/>} /><Route path="programs" element={me.membership?<ProgramsPage me={me}/>:<Navigate to="/app" replace/>} /><Route path="saw" element={me.permissions.includes('saw.view')?<SawPage me={me}/>:<Navigate to="/app" replace/>}/><Route path="organization" element={me.is_super_admin?<OrganizationPage/>:<Navigate to="/app" replace/>}/><Route path="*" element={<Navigate to="/app" replace/>}/></Routes></div></div></div>;
}

function App() {
  const [me,setMe]=useState<Me|null>(null);
  const [loading,setLoading]=useState(true);
  const refresh = useCallback(async()=>{
    const response=await api.get<{data:Me}>('/api/v1/me');setMe(response.data.data);
  },[]);
  useEffect(()=>{
    if (!window.location.pathname.startsWith('/app')) { setLoading(false); return; }
    refresh().catch(()=>setMe(null)).finally(()=>setLoading(false));
  },[refresh]);
  const logout=async()=>{try{await api.post('/logout');}finally{setMe(null);window.location.assign('/login');}};
  return <Routes><Route path="/" element={<Landing/>}/><Route path="/login" element={me?<Navigate to="/app" replace/>:<Login onLogin={refresh}/>}/><Route path="/app/*" element={loading?<div className="page-loading">Memuat workspace…</div>:me?<DashboardShell me={me} onLogout={logout}/>:<Navigate to="/login" replace/>}/><Route path="*" element={<Navigate to="/" replace/>}/></Routes>;
}

createRoot(document.getElementById('root')!).render(<BrowserRouter><App/></BrowserRouter>);
