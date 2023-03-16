<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
        <table>
            <tr>
                <th>Course code</th>
                <th>Course name</th>
                <th>Credit hours</th>
                <th>Course type</th>
            </tr>
            @foreach ($courses as $course )
                <tr>
                    <td>{{ $course['course_code'] }}</td>
                    <td>{{ $course['name'] }}</td>
                    <td>{{ $course['credit_hours'] }}</td>
                    <td>{{ $course['course_type'] }}</td>
                </tr>
            @endforeach
        </table>
        <form name="course-query" method="GET" action="{{ url('transform') }}">
            <label for="type">Course type:</label><br>
            <input type="text" id="type" name="type"><br>
            <label for="hours">Course hours:</label><br>
            <input type="number" id="hours" name="hours">
            <button type="submit">go</button>
        </form>
    </body>
</html>
