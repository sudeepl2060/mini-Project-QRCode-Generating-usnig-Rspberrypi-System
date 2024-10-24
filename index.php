<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate QR Code for College</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 20px;
        }
        nav {
            background-color: #007bff;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        nav a {
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            margin: 0 10px;
            border-radius: 5px;
            background-color: #0056b3;
            display: inline-block;
            transition: background-color 0.3s;
        }
        nav a:hover {
            background-color: #003d82;
        }
        h1 {
            background-color: #161B7F;
            color: yellow;
            padding: 15px;
            border-radius: 5px;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .btn {
            margin: 5px;
        }
        .hidden {
            display: none;
        }
        .qr-code {
            margin-top: 20px;
        }
    </style>
    <script>
    $(document).ready(function() {
        // Load colleges
        $.get('process.php?action=get_colleges', function(data) {
            $('#colleges').html(data);
        });

        // Handle college change
        $('#colleges').on('change', function() {
            var college_id = $(this).val();
            resetSelects(['#departments', '#labs', '#systems', '#components', '#campus_parts', '#trees']);
            $('#qr_code').html('');
            $.get('process.php?action=get_departments&college_id=' + college_id, function(data) {
                $('#departments').html(data);
            });
            $.get('process.php?action=get_campus_parts', function(data) {
                $('#campus_parts').html(data);
            });
            $('#options').show();
        });

        // Show/hide containers
        $('#departments_button').on('click', function() {
            toggleContainers('#departments_container', '#green_campus_container');
        });

        $('#green_campus_button').on('click', function() {
            toggleContainers('#green_campus_container', '#departments_container');
        });

        // Handle department change
        $('#departments').on('change', function() {
            var department_id = $(this).val();
            resetSelects(['#labs', '#systems', '#components']);
            $('#qr_code').html('');
            $.get('process.php?action=get_labs&department_id=' + department_id, function(data) {
                $('#labs').html(data);
            });
        });

        // Handle lab change
        $('#labs').on('change', function() {
            var lab_id = $(this).val();
            resetSelects(['#systems', '#components']);
            $('#qr_code').html('');
            $.get('process.php?action=get_systems&lab_id=' + lab_id, function(data) {
                $('#systems').html(data);
            });
        });

        // Handle system change
        $('#systems').on('change', function() {
            var system_id = $(this).val();
            resetSelects(['#components']);
            $('#qr_code').html('');
            $.get('process.php?action=get_components&system_id=' + system_id, function(data) {
                $('#components').html(data);
            });
        });

        // Handle component change
        $('#components').on('change', function() {
            var component_id = $(this).val();
            $('#qr_code').html('');
            $.get('process.php?action=generate_qr_component&component_id=' + component_id, function(data) {
                $('#qr_code').html(data);
            });
        });

        // Handle campus part change
        $('#campus_parts').on('change', function() {
            var campus_part_id = $(this).val();
            resetSelects(['#trees']);
            $('#qr_code').html('');
            $.get('process.php?action=get_trees&campus_part_id=' + campus_part_id, function(data) {
                $('#trees').html(data);
            });
        });

        // Handle tree change
        $('#trees').on('change', function() {
            var tree_id = $(this).val();
            $('#qr_code').html('');
            $.get('process.php?action=generate_qr_tree&tree_id=' + tree_id, function(data) {
                $('#qr_code').html(data);
            });
        });

        function resetSelects(selectors) {
            selectors.forEach(function(selector) {
                $(selector).html('');
            });
        }

        function toggleContainers(showSelector, hideSelector) {
            $(showSelector).show();
            $(hideSelector).hide();
        }
    });
    </script>
</head>
<body>
    <div class="container">
        <h1 class="text-center">Generate QR Code for College</h1>
        <select id="colleges" class="form-select mb-3"></select>
        <div id="options" class="hidden">
            <div class="d-flex justify-content-center mb-3">
                <button id="departments_button" class="btn btn-primary">Departments</button>
                <button id="green_campus_button" class="btn btn-success">Green Campus</button>
            </div>
            <div id="departments_container" class="hidden">
                <h2>Select Department</h2>
                <select id="departments" class="form-select mb-3"></select>
                <h2>Select Lab</h2>
                <select id="labs" class="form-select mb-3"></select>
                <h2>Select System</h2>
                <select id="systems" class="form-select mb-3"></select>
                <h2>Select Component</h2>
                <select id="components" class="form-select mb-3"></select>
            </div>
            <div id="green_campus_container" class="hidden">
                <h2>Select Part of Campus</h2>
                <select id="campus_parts" class="form-select mb-3"></select>
                <h2>Select Tree</h2>
                <select id="trees" class="form-select mb-3"></select>
            </div>
        </div>
        <div id="qr_code" class="qr-code text-center"></div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
