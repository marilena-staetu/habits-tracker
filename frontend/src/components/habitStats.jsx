import HabitsContext from "./context/habitContext";
import {useContext} from "react";;

function HabitStats() {
    const {habits} = useContext(HabitsContext);

    return (<div className='habits-stats'>
        {habits ? (<h4>{habits.length} {habits.length === 1 ? "Habit" : "Habits"} </h4>) : "There are no habits"}

    </div>)
}

export default HabitStats