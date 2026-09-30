import React, { useState, useEffect } from 'react';
import { BrowserRouter, Routes, Route, Navigate, useLocation, useNavigate, useSearchParams } from 'react-router-dom';
import Navbar from './components/Navbar';
import Footer from './components/Footer';
import GateScan from './pages/GateScan';
import MyQrCard from './pages/MyQrCard';
import VisitsDashboard from './pages/VisitsDashboard';
import AdminSidebar from './components/AdminSidebar';
import ManualCheckinModal from './components/ManualCheckinModal';
import AddMemberModal from './components/AddMemberModal';
import MemberQrModal from './components/MemberQrModal';
import MemberDirectoryModal from './components/MemberDirectoryModal';
import ImportMemberModal from './components/ImportMemberModal';
import { getStoredUser, fetchVisitStats, getExportCsvUrl } from './api/gate';

// Login dikelola portal Blade (satu pintu), bukan SPA ini.
function LoginRedirect() {
    useEffect(() => {
        window.location.replace('/login');
    }, []);

    return null;
}

function AppContent() {
    const location = useLocation();
    const navigate = useNavigate();
    const [searchParams] = useSearchParams();
    const [user, setUser] = useState(getStoredUser());
    const [stats, setStats] = useState(null);

    // Global sidebar modals
    const [manualModalOpen, setManualModalOpen] = useState(false);
    const [manualDefaultType, setManualDefaultType] = useState('member');
    const [addMemberModalOpen, setAddMemberModalOpen] = useState(false);
    const [directoryModalOpen, setDirectoryModalOpen] = useState(false);
    const [directoryInitialRole, setDirectoryInitialRole] = useState('');
    const [selectedMember, setSelectedMember] = useState(null);
    const [memberQrModalOpen, setMemberQrModalOpen] = useState(false);
    const [importMemberModalOpen, setImportMemberModalOpen] = useState(false);

    // Auth sync
    useEffect(() => {
        const syncAuth = () => setUser(getStoredUser());
        window.addEventListener('auth-changed', syncAuth);
        window.addEventListener('storage', syncAuth);
        return () => {
            window.removeEventListener('auth-changed', syncAuth);
            window.removeEventListener('storage', syncAuth);
        };
    }, []);

    const isStaff = Boolean(user && (user.is_staff || user.role === 'admin_madrasah' || user.role === 'pustakawan'));

    // Load stats for admin sidebar counter badges
    const loadStats = () => {
        if (isStaff) {
            fetchVisitStats()
                .then((res) => setStats(res.data))
                .catch(() => {});
        }
    };

    useEffect(() => {
        loadStats();
        const handleVisitsUpdated = () => loadStats();
        window.addEventListener('visits-updated', handleVisitsUpdated);
        return () => window.removeEventListener('visits-updated', handleVisitsUpdated);
    }, [isStaff, location.pathname]);

    const activeTab = searchParams.get('tab') || 'dashboard';

    const handleSelectTab = (newTab) => {
        navigate(`/visits?tab=${newTab}`);
    };

    const handleOpenManual = (type = 'member') => {
        setManualDefaultType(type);
        setManualModalOpen(true);
    };

    const handleManualSuccess = () => {
        window.dispatchEvent(new CustomEvent('visits-updated'));
        loadStats();
    };

    const handleAddMemberSuccess = (data) => {
        window.dispatchEvent(new CustomEvent('visits-updated'));
        loadStats();
        if (data?.user) {
            setSelectedMember(data.user);
            setMemberQrModalOpen(true);
        }
    };

    // =========================================================================
    // 1. ADMIN / STAFF LAYOUT: ONLY SIDEBAR! (NO NAVBAR, NO FOOTER)
    // =========================================================================
    if (isStaff) {
        return (
            <div className="min-h-screen bg-[#FAFCFF] text-[#07132B] flex flex-col md:flex-row selection:bg-[#0B1B3D] selection:text-white">
                {/* Dedicated Sticky Admin Sidebar */}
                <AdminSidebar
                    activeTab={location.pathname === '/visits' ? activeTab : null}
                    onSelectTab={handleSelectTab}
                    onOpenManualCheckin={handleOpenManual}
                    onOpenAddMember={() => setAddMemberModalOpen(true)}
                    exportUrl={getExportCsvUrl()}
                    stats={stats}
                    currentUser={user}
                    onOpenMemberDirectory={(role = '') => {
                        setDirectoryInitialRole(role);
                        setDirectoryModalOpen(true);
                    }}
                    onOpenImportExcel={() => setImportMemberModalOpen(true)}
                />

                {/* Main Content Workspace */}
                <div className="flex-1 flex flex-col min-w-0 min-h-screen overflow-x-hidden bg-[#FAFCFF]">
                    <Routes>
                        <Route path="/" element={<Navigate to="/visits" replace />} />
                        <Route path="/visits" element={<VisitsDashboard />} />
                        <Route path="/gate" element={<GateScan />} />
                        <Route path="/my-qr" element={<MyQrCard />} />
                        <Route path="*" element={<Navigate to="/visits" replace />} />
                    </Routes>
                </div>

                {/* Sidebar Modals */}
                <ManualCheckinModal
                    isOpen={manualModalOpen}
                    defaultType={manualDefaultType}
                    onClose={() => setManualModalOpen(false)}
                    onSuccess={handleManualSuccess}
                />

                <AddMemberModal
                    isOpen={addMemberModalOpen}
                    onClose={() => setAddMemberModalOpen(false)}
                    onSuccess={handleAddMemberSuccess}
                />

                <MemberDirectoryModal
                    isOpen={directoryModalOpen}
                    initialRole={directoryInitialRole}
                    onClose={() => setDirectoryModalOpen(false)}
                    onOpenQr={(member) => {
                        setSelectedMember(member);
                        setMemberQrModalOpen(true);
                    }}
                    onOpenAddMember={() => {
                        setDirectoryModalOpen(false);
                        setAddMemberModalOpen(true);
                    }}
                />

                <MemberQrModal
                    isOpen={memberQrModalOpen}
                    userId={selectedMember?.id}
                    memberInfo={selectedMember}
                    onClose={() => {
                        setMemberQrModalOpen(false);
                        setSelectedMember(null);
                    }}
                />

                <ImportMemberModal
                    isOpen={importMemberModalOpen}
                    onClose={() => setImportMemberModalOpen(false)}
                    onSuccess={() => {
                        window.dispatchEvent(new CustomEvent('visits-updated'));
                    }}
                />
            </div>
        );
    }

    // =========================================================================
    // 2. PUBLIC / STUDENT LAYOUT: Uses Navbar, NO Sidebar
    // =========================================================================
    return (
        <div className="min-h-screen bg-[#F8FAFC] text-[#07132B] flex flex-col selection:bg-[#0B1B3D] selection:text-white">
            <Navbar />
            <div className="flex-1 flex flex-col">
                <Routes>
                    <Route path="/" element={<Navigate to="/gate" replace />} />
                    <Route path="/gate" element={<GateScan />} />
                    <Route path="/my-qr" element={<MyQrCard />} />
                    <Route path="/visits" element={<VisitsDashboard />} />
                    <Route path="/login" element={<LoginRedirect />} />
                    <Route path="*" element={<Navigate to="/gate" replace />} />
                </Routes>
            </div>
            <Footer />
        </div>
    );
}

export default function App() {
    return (
        <BrowserRouter basename="/presensi">
            <AppContent />
        </BrowserRouter>
    );
}
