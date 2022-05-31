import React, {useState, useContext, useEffect} from "react";
import {useNavigate} from "react-router-dom";
import Joi from 'joi-browser';
import Input from "./common/input";
import Card from "./common/card";
import UserContext from "./context/userContext";

const RegisterForm = () => {
    const [user, setUser] = useState({email: '', password: '', username: ''});
    const [errors, setErrors] = useState({});

    const {register, responseErrors} = useContext(UserContext);

    useEffect(() => {
            const myResponseErrors = {...errors};
            myResponseErrors.email = responseErrors;
            setErrors(myResponseErrors);

    }, [responseErrors])


    const navigate = useNavigate();

    const schema = {
        email: Joi.string().required().email().label("Username"),
        password: Joi.string().required().min(5).label("Password"),
        username: Joi.string().required().label("Name")
    }

    const validate = () => {
        const { error } = Joi.validate(user, schema, { abortEarly: false});
        if (!error) return null;

        const validationErrors = {};
        for (let item of error.details)
            validationErrors[item.path[0]] = item.message;

        return validationErrors;
    };

    const handleSubmit = e => {
        e.preventDefault();

        const validationErrors = validate();
        if (validationErrors) setErrors({errors: validationErrors});
        setErrors({ errors: validationErrors || {} });
        if (errors.value) return;


        doSubmit();
    };

    const doSubmit = () => {

        register(user);

    }

    const validateProperty = ({ name, value }) => {
        const obj = { [name] : value };
        const mySchema = { [name]: schema[name]};
        const { error } = Joi.validate(obj, mySchema);
        return error ? error.details[0].message : null;
    };

    const handleChange = ({ currentTarget: input }) => {
        const myErrors = { ...errors };
        const errorMessage = validateProperty(input);
        if (errorMessage) myErrors[input.name] = errorMessage;
        else delete myErrors[input.name];

        const myData = {...user};
        myData[input.name] = input.value;

        setUser(myData);
        setErrors(myErrors);
    };

    const renderInput = (name, label, type = 'text') => {
        return (
            <Input
                type={type}
                name={name}
                label={label}
                value={user[name]}
                error={errors[name]}
                onChange={handleChange}
            />
        );
    }

    const renderButton = (label) => {
        return (
            <button
                disabled={!!validate()}
                className="btn btn-primary"
            >
                {label}
            </button>
        );
    };



    return (
        <Card>
            <h1>Register</h1>
            <form onSubmit={handleSubmit}>
                {renderInput('email', 'Email')}
                {renderInput('password', 'Password', 'password')}
                {renderInput('username', 'Username')}
                {renderButton('Register')}
            </form>
        </Card>
    );


}

export default RegisterForm;