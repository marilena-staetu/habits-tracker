import PropTypes from "prop-types";

function Card({children}) {
    return (
        <div className="card mb-2">
            <div className="card-body">
                {children}
            </div>
        </div>
    )
}

Card.propTypes = {
    children: PropTypes.node.isRequired,
}

export default Card