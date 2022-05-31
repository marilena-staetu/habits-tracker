import {createContext, useEffect, useState,} from "react";
import {useNavigate} from "react-router-dom";

const UserContext = createContext();

export const UserProvider = ({children}) => {
    const [user, setUser] = useState(null)
    const [responseErrors, setResponseErrors] = useState('')

    useEffect(() => {
        getUser();
    }, []);

    const navigate = useNavigate();

    // Login
    const login = async (user) => {
        const response = await fetch('http://localhost:8000/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Access-Control-Allow-Credentials' : 'true',
            },
            credentials: 'include',
            body: JSON.stringify({email: user.email, password: user.password}),

        })
            .then(response => {
                if (response.ok) {
                    navigate('/', { replace: true });

                }
                else {
                    setResponseErrors(response.statusText);
                }
            })
            .catch((err) => {
                window.alert(err);
            });
    }

    // Register users
    const register = async (user) => {

        const response = await fetch('http://localhost:8000/api/users', {
            method: 'POST',
            headers: {
                'content-type': 'application/json'
            },
            body: JSON.stringify({email: user.email, password: user.password, username: user.username}),
        });

        const data = await response;

        if (response.ok) {
            let json = await response.json();
            setUser({"email": json.email, "id": json.id, "username": json.username});

            login(user);
        } else {
            setResponseErrors(data.statusText);
        }
    }

    const getUser = async () => {
        const response = await fetch('http://localhost:8000/api/me', {
            credentials: 'include',
        })
            .then(response => {
                if (response.ok) {
                    return response.json();
                } else {

                }
        })
            .then(json => {
                setUser(json);
            })
            .catch((err) => {
                window.alert(err);
            });
    }

    const logout = async () => {
        const res = await fetch('http://localhost:8000/logout', {
            credentials: 'include'
        });

        if (res.ok) {
            setUser(null);
            navigate('/');
        }

    }

    return <UserContext.Provider value={{
        user,
        responseErrors,
        register,
        login,
        logout,
    }}>{children}</UserContext.Provider>
};

export default UserContext;