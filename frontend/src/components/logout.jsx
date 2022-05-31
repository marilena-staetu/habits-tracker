import {useContext, useEffect} from "react";
import UserContext from "./context/userContext";

const Logout = () => {
    const {logout} = useContext(UserContext);

    useEffect(() => {
        logout();
    }, []);

    return <h2>You have been successfully logged out!</h2>

}

export default Logout;

