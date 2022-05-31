import {useContext} from "react";
import HabitsContext from "./context/habitContext";
import HabitItem from "./habitItem";
import Spinner from "./common/spinner";

function HabitList() {
    const {habits, isLoading} = useContext(HabitsContext);

    if(!isLoading && (!habits || habits.length === 0)) {
        return <p>No Habits To Show</p>
    }

    return isLoading ? <Spinner /> : (
        <div>
            {habits.map((habit) => (
                <HabitItem key={habit.id} habit={habit} />
            ))}
        </div>
    );
}

export default HabitList;
