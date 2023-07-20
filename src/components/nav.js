import React from "react";

export default function Nav() {
    return <>
        <nav className="mt-2">
            <div className="container d-flex justify-content-between">
                <div>
                    {/* Your logo or navigation links */}
                    <ul className="navbar-nav ">
                        {/* Add more navigation links if needed */}
                    </ul>
                </div>
                <div className="d-flex align-items-center">
                    {/* Donate link */}
                    <a className="nav-link link-pixel" href="/donate">Donate</a>
                    {/* Contributing link */}
                    <a className="nav-link link-pixel" href="/contributing">Contributing</a>
                    {/* Discord link */}
                    <a className="nav-link link-pixel" href="https://discord.gg/SQD7yvuZ23" target="_blank" rel="noreferrer">
                        Discord
                    </a>
                </div>
            </div>
        </nav>
    </>;
}