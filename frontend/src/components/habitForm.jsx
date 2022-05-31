import { useContext, useState, useEffect } from "react";
import Card from "./common/card";
import Button from "./common/button";
import HabitsContext from "./context/habitContext";

const HabitForm = () => {
    const [text, setText] = useState('');
    const [btnDisabled, setBtnDisabled] = useState(true);
    const [message, setMessage] = useState('');

    const { addHabit, habitEdit, updateHabit } = useContext(HabitsContext);

    useEffect(() => {
        if(habitEdit.edit === true) {
            setBtnDisabled(false);
            setText(habitEdit.habit.name);
        }
    }, [habitEdit])

    const handleTextChange = (e) => {
        const inputText = e.target.value;

        if(inputText === '') {
            setBtnDisabled(true);
            setMessage(null);
        } else if(inputText !== '' && text.trim().length >= 50) {
            setMessage('Text must be less than 50 characters');
            setBtnDisabled(true);
        } else {
            setMessage(null);
            setBtnDisabled(false);
        }

        setText(e.target.value);
    }

    const handleSubmit = (e) => {
        e.preventDefault();

        const newHabit = {
            name: text,
        };

        if(habitEdit.edit === true) {
            updateHabit(habitEdit.habit.id, newHabit)
        } else {
            addHabit(newHabit);
        }

        setBtnDisabled(true);
        setText('');
    }

    return (
        <Card>
            <form onSubmit={handleSubmit}>
                <h2>What do you want to develop next?</h2>
                <div className="input-group ">
                    <input onChange={handleTextChange} type="text" placeholder="Add a habit" value={text} />
                    <Button type="submit" children="Add" isDisabled={btnDisabled} />
                </div>
                {message && <div className='message'>{message}</div>}
            </form>
        </Card>
    );
}

export default HabitForm;