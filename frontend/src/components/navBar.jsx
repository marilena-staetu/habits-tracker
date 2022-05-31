import {NavLink, Link} from "react-router-dom";
import PropTypes from "prop-types";

function NavBar({ title }) {

    return (
        <nav className="navbar navbar-expand-lg navbar-light bg-light">
            <div className="container-fluid">
                <Link className="navbar-brand" to="/habits">{title}</Link>
                <button className="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span className="navbar-toggler-icon"></span>
                </button>
                <div className="collapse navbar-collapse" id="navbarNav">
                    <ul className="navbar-nav">
                        <li className="nav-item">
                            <NavLink className="nav-link" to="/login">Login</NavLink>
                        </li>
                        <li className="nav-item">
                            <NavLink className="nav-link" to="/register">Register</NavLink>
                        </li>
                        <li className="nav-item d-flex">
                            <NavLink className="nav-link" to="/logout">Logout</NavLink>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    )
};

NavBar.defaultProps = {
    title: "Habits",
}

NavBar.propTypes = {
    title: PropTypes.string
}

export default NavBar;