import React from 'react';
import {Routes, Route, Navigate} from "react-router-dom";
import NotFound from "./components/notFound";
import NavBar from "./components/navBar";
import Habits from "./components/habits";
import LoginForm from "./components/loginForm";
import RegisterForm from "./components/registerForm";
import AboutIconLink from "./components/aboutIconLink";
import AboutPage from "./components/aboutPage";
import {UserProvider} from "./components/context/userContext";
import Logout from "./components/logout";

function App() {
    return (
        <React.Fragment>
            <UserProvider>
                <NavBar />
                <main className="container">
                    <Routes>
                        <Route path="/login" element={<LoginForm />} />
                        <Route path="/logout" element={<Logout />} />
                        <Route path="/register" element={<RegisterForm />} />
                        <Route path="/habits" element={<Habits />} />
                        <Route path="/not-found" element={<NotFound />} />
                        <Route path="/" element={<Navigate to="/habits" replace />} />
                        <Route path="/*" element={<Navigate to="/not-found" replace />} />
                        <Route path="/about" element={<AboutPage />} />
                    </Routes>
                </main>
                <AboutIconLink />
            </UserProvider>
        </React.Fragment>
    );
}

export default App;