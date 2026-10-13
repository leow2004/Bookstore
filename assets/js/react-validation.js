const e = React.createElement;

function RegistrationForm() {
    const [name, setName] = React.useState("");
    const [phone, setPhone] = React.useState("");
    const [email, setEmail] = React.useState("");
    const [password, setPassword] = React.useState("");
    const [errors, setErrors] = React.useState({});

    React.useEffect(() => {
        let newErrors = {};

        if (name.length > 0 && !/^[a-zA-Z ]+$/.test(name)) {
            newErrors.name = "Name must contain letters only.";
        }

        if (phone.length > 0 && !/^[0-9]{10}$/.test(phone)) {
            newErrors.phone = "Phone number must be exactly 10 digits.";
        }

        if (email.length > 0 && !/\S+@\S+\.\S+/.test(email)) {
            newErrors.email = "Please enter a valid email address.";
        }

        if (password.length > 0 && password.length < 6) {
            newErrors.password = "Password must be at least 6 characters.";
        }

        setErrors(newErrors);
    }, [name, phone, email, password]);

    return e("form", { method: "POST", action: "register.php" },
        e("div", null,
            e("label", null, "Name: "),
            e("input", {
                type: "text",
                name: "name",
                value: name,
                onChange: (event) => setName(event.target.value),
                required: true
            }),
            errors.name && e("p", { style: { color: "red" } }, errors.name)
        ),
        e("div", null,
            e("label", null, "Phone: "),
            e("input", {
                type: "text",
                name: "phone",
                value: phone,
                onChange: (event) => setPhone(event.target.value),
                required: true
            }),
            errors.phone && e("p", { style: { color: "red" } }, errors.phone)
        ),
        e("div", null,
            e("label", null, "Email: "),
            e("input", {
                type: "email",
                name: "email",
                value: email,
                onChange: (event) => setEmail(event.target.value),
                required: true
            }),
            errors.email && e("p", { style: { color: "red" } }, errors.email)
        ),
        e("div", null,
            e("label", null, "Password: "),
            e("input", {
                type: "password",
                name: "password",
                value: password,
                onChange: (event) => setPassword(event.target.value),
                required: true
            }),
            errors.password && e("p", { style: { color: "red" } }, errors.password)
        ),
        e("button", { type: "submit" }, "Register")
    );
}

const root = ReactDOM.createRoot(document.getElementById("react-form"));
root.render(e(RegistrationForm));