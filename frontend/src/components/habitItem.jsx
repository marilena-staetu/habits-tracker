import { FaTimes, FaEdit } from 'react-icons/fa';
import PropTypes from "prop-types";
import Card from "./common/card";
import {useContext} from "react";
import HabitsContext from "./context/habitContext";

function HabitItem({ habit }) {
    const { deleteHabit, editHabit } = useContext(HabitsContext);

    return (
        <Card>
            {habit && habit.name}
            <button onClick={() => deleteHabit(habit.id)} className="close">
                <FaTimes color='purple' />
            </button>
            <button onClick={() => editHabit(habit)} className="edit">
                <FaEdit color='purple' />
            </button>
        </Card>
    );
}

HabitItem.propTypes = {
    habit: PropTypes.object.isRequired,
}

export default HabitItem;