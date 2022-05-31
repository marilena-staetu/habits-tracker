import Card from "./common/card";
import {Link} from "react-router-dom";
import React from "react";

function AboutPage() {
    return (
        <Card>
            <div className="about">
                <h1>About This Project</h1>
                <p>This is a project meant to help users develop new habits.</p>
                <Link to={"/"}>Back to Home</Link>
            </div>
        </Card>
    )
};

export default AboutPage;